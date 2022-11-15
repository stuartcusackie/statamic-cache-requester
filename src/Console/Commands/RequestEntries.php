<?php

namespace stuartcusackie\StatamicCacheRequester\Console\Commands;

use Illuminate\Console\Command;
use Statamic\Facades\Entry;
use stuartcusackie\StatamicCacheRequester\Jobs\RequestUrl;

class RequestEntries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'requester:entries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Visits every routable Statamic entry.';

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
                    RequestUrl::dispatch(url($entry->url));
                    $count++;
                }
                catch(\Throwable $e){
                    throw new \Exception('Could not queue an entry for cache requesting.');
                }

            }

        }

        $this->info("{$count} entries have been queued for public url retrieval.");

        return 0;
    }
}
