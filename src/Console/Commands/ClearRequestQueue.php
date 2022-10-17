<?php

namespace stuartcusackie\StatamicCacheRequester\Console\Commands;

use Illuminate\Console\Command;
use stuartcusackie\StatamicCacheRequester\StatamicGlideRequester;

class ClearRequestQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'requester:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears the requester queue.';
    
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
