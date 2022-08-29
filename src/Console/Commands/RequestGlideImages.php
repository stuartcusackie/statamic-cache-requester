<?php

namespace stuartcusackie\StatamicGlideRequester\Console\Commands;

use Illuminate\Console\Command;
use Statamic\Facades\Entry;
use Statamic\Facades\Asset;
use Illuminate\Support\Facades\Http;
use simplehtmldom\HtmlDocument;
use stuartcusackie\StatamicGlideRequester\Jobs\VisitGlideUrl;
use Illuminate\Support\Str;

class RequestGlideImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'glide:request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Visits every routable Statamic entry and adds each glide image url to a queue for retrieval.';

    /**
     * The total images queued
     */
    protected $images = 0;
    
    /**
     * The source attributes
     * to search for
     */
    protected $sourceAttributes = [
        'srcset',
        'lazy-srcset',
        'data-srcset'
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->call('queue:clear', ['connection' => 'redis',  '--queue' => 'gliderequester']);

        // $this->checkEntries();

        if(config('statamic-glide-requester.asset_view_path')){
            $this->checkAssets();
        }
        
        $this->info($this->images . ' glide images queued for retrieval. You can now run the gliderequester queue on redis.');

        return 0;
    }

    /**
     * Check all entry urls for matching
     * source attributes and add any glide images
     * to the queue.
     * 
     * @return void
     */
    protected function checkEntries() {

        $this->info('Checking for glide images in all routable entries. This could take quite a while...');

        foreach(Entry::all() as $entry) {

            if($entry->url) {
                
                $response = Http::get(url($entry->url));

                if($response->failed()) {
                    $this->info('An entry could not be retrieved: ' . url($entry->url));
                    continue;
                }

                $this->processResponse($response->body(), 'entry');
            }
        }

    }

    /**
     * Check all entry urls for matching
     * source attributes and add any glide images
     * to the queue.
     * 
     * @return void
     */
    protected function checkAssets() {

        $this->info('Checking for glide images in all assets. This could take quite a while...');

        foreach(Asset::all() as $asset) {

            $response = Http::post(url(config('statamic-glide-requester.asset_view_path')), [
                'id' => $asset->id
            ]);

            if($response->failed()) {
                $this->info('An asset could not be retrieved: ' . $asset->id);
                continue;
            }

            $this->processResponse($response->body(), 'asset');
        }
    }

    /**
     * Check all asset urls for matching
     * source attributes and add any glide images
     * to the queue.
     * 
     * @return void
     */
    protected function processResponse($responseBody, $type = 'entry') {

        $htmlClient = new HtmlDocument();
        $htmlClient->load($responseBody);

        // Handle picture els containing img and sources
        foreach($htmlClient->find('picture') as $pictureEl) {

            $this->processElement($pictureEl->find('img', 0), $type);

            foreach($pictureEl->find('source') as $sourceEl) {
                $this->processElement($sourceEl, $type);
            }
        }

        // Handle non-picture images
        foreach($htmlClient->find('img') as $imgEl) {

            if($imgEl->parentNode()->nodeName() != 'picture') {
                $this->processElement($imgEl, $type);
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
    protected function processElement($el, $type = 'entry') {

        foreach(config('statamic-glide-requester.src_attributes') as $attr) {

            if($el->hasAttribute($attr)) {

                // Could be multiple comma-separted source paths
                foreach(explode(', ', $el->getAttribute($attr)) as $path) {

                    // Remove any source dimensions
                    $path = explode(' ', $path)[0];

                    if(Str::startsWith($path, '/img/')) {
                        $this->info('Adding ' . $type . ' image job: ' . url($path));
                        VisitGlideUrl::dispatch(url($path));
                        $this->images++;
                    }
                }
            }
        }
    }
}
