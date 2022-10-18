<?php

namespace stuartcusackie\StatamicCacheRequester\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use simplehtmldom\HtmlDocument;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class RequestUrl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The url to request
     *
     * @var string
     */
    public $url;

    /**
     * Whether to process images or not
     *
     * @var bool
     */
    public $processImages;

    /**
     * The http request type
     *
     * @var string
     */
    public $requestType;

    /**
     * The optional post data
     *
     * @var string
     */
    public $postData;

    /**
     * Create a new job instance.
     *
     * @param  string  $url
     * @param  bool  $processImages
     * @param  string  $requestType
     * @param  array  $postData
     * @return void
     */
    public function __construct(string $url, bool $processImages = false, string $requestType = 'get', array $postData = [])
    {
        $this->onConnection(config('statamic-cache-requester.queue_connection'));
        $this->onQueue(config('statamic-cache-requester.queue_name'));

        $this->url = $url;
        $this->processImages = $processImages;
        $this->requestType = $requestType;
        $this->postData = $postData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->requestType == 'get') {
            $response = Http::get($this->url);
        }
        else if($this->requestType == 'post') {
            $response = Http::post($this->url, $this->postData);
        }

        if($response->failed()) {
            return;
        }

        if($this->processImages) {
            $this->processImages($response->body());
        }
    }

    /**
     * Find html elements matching
     * source attributes and add any matching urls
     * to the queue.
     * 
     * @return void
     */
    protected function processImages($responseBody) {

        $htmlClient = new HtmlDocument();
        $htmlClient->load($responseBody);

        // Handle picture els containing img and sources
        foreach($htmlClient->find('picture') as $pictureEl) {

            $this->processElement($pictureEl->find('img', 0));

            foreach($pictureEl->find('source') as $sourceEl) {
                $this->processElement($sourceEl);
            }
        }

        // Handle non-picture images
        foreach($htmlClient->find('img') as $imgEl) {

            if($imgEl->parentNode()->nodeName() != 'picture') {
                $this->processElement($imgEl);
            }

        }
    }

    /**
     * Check an element for all defined
     * source attributes and add each image 
     * as a job.
     * 
     * @return void
     */
    protected function processElement($el) {

        // Search for any element attributes
        foreach(config('statamic-cache-requester.src_attributes') as $attr) {

            if($el->hasAttribute($attr)) {

                // Could be multiple comma-separted source paths
                foreach(explode(', ', $el->getAttribute($attr)) as $path) {

                    // Remove any source dimensions
                    $path = explode(' ', $path)[0];

                    // Dispatch a new job for a simple image
                    if(Str::startsWith($path, '/img/')) {
                        $this->dispatch(url($path));
                    }
                }
            }
        }

        // Search for any post data attributes (single image views)
        if(config('statamic-cache-requester.asset_view_path') && count(config('statamic-cache-requester.post_data_attributes'))) {

            foreach(config('statamic-cache-requester.post_data_attributes') as $attr) {

                if($el->hasAttribute($attr)) {
                        
                    // Dispatch a new job for a post request image
                    $this->dispatch(url(config('statamic-cache-requester.asset_view_path')), false, 'post', [
                        'id' => $el->getAttribute($attr)
                    ]);
                    
                }
            }
        }
    }
}
