<?php

namespace stuartcusackie\StatamicGlideRequester\Console\Commands;

use Illuminate\Console\Command;
use Statamic\Facades\Entry;
use Statamic\Facades\Asset;
use stuartcusackie\StatamicGlideRequester\Jobs\FindElementsAtUrl;

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
     * The total urls queue
     */
    protected $urls = 0;
    
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

        $this->checkEntries();
        
        $this->info($this->urls . ' urls queued for processing. You can now run the gliderequester queue on redis.');

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
                FindElementsAtUrl::dispatch(url($entry->url));
                $this->urls++;
            }
        }

    }
}
