<?php

namespace stuartcusackie\StatamicCacheRequester\Console\Commands;

use Illuminate\Console\Command;
use Statamic\Facades\Entry;
use stuartcusackie\StatamicCacheRequester\Jobs\RequestUrl;

class RequestImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'requester:images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Visits every routable Statamic entry and adds each image url to a queue for retrieval.';

    /**
     * The total urls queue
     */
    protected $urls = 0;
    
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->call('requester:clear');
        $count = 0;

        foreach(Entry::all() as $entry) {

            if($entry->url) {

                try {
                    RequestUrl::dispatch(url($entry->url), true);
                    $count++;
                }
                catch(\Throwable $e){
                    throw new \Exception('Could not queue entry for image cache requesting.');
                }

            }
        }

        $this->info("{$count} entries have been queued for public url and image retrieval.");

        return 0;
    }
}
