<?php

namespace stuartcusackie\StatamicCacheRequester\Console\Commands;

use Illuminate\Console\Command;
use stuartcusackie\StatamicCacheRequester\StatamicCacheRequester;

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
        StatamicCacheRequester::queueAllEntries();

        return 0;
    }
}
