<?php

namespace stuartcusackie\StatamicGlideRequester\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use simplehtmldom\HtmlDocument;
use Illuminate\Support\Str;
use stuartcusackie\StatamicGlideRequester\Jobs\VisitGlideUrl;

class FindElementsAtUrl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The glide image url
     *
     * @var string
     */
    public $url;

    /**
     * The http request type
     *
     * @var string
     */
    public $type;

    /**
     * The optional post data
     *
     * @var string
     */
    public $postData;

    /**
     * Create a new job instance.
     *
     * @param  string  $type
     * @param  string  $url
     * @param  array  $postData
     * @return void
     */
    public function __construct(string $url, $type = null, $postData = null)
    {
        $this->onConnection('redis');
        $this->onQueue('gliderequester');

        $this->url = $url;
        $this->type = $type ?? 'get';
        $this->postData = $postData ?? [];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->type == 'get') {
            $response = Http::get($this->url);
        }
        else if($this->type == 'post') {
            $response = Http::post($this->url, $this->postData);
        }

        if($response->failed()) {
            return;
        }

        $this->processResponse($response->body());
    }

    /**
     * Check all asset urls for matching
     * source attributes and add any glide images
     * to the queue.
     * 
     * @return void
     */
    protected function processResponse($responseBody) {

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
        foreach(config('statamic-glide-requester.src_attributes') as $attr) {

            if($el->hasAttribute($attr)) {

                // Could be multiple comma-separted source paths
                foreach(explode(', ', $el->getAttribute($attr)) as $path) {

                    // Remove any source dimensions
                    $path = explode(' ', $path)[0];

                    if(Str::startsWith($path, '/img/')) {
                        VisitGlideUrl::dispatch(url($path));
                    }
                }
            }
        }

        // Search for any post data attributes (single image views)
        if(config('statamic-glide-requester.asset_view_path') && count(config('statamic-glide-requester.post_data_attributes'))) {

            foreach(config('statamic-glide-requester.post_data_attributes') as $attr) {

                if($el->hasAttribute($attr)) {

                    $this->dispatch(url(config('statamic-glide-requester.asset_view_path')), 'post', [
                        'id' => $el->getAttribute($attr)
                    ]);
                }
            }
        }
    }
}
