<?php

namespace stuartcusackie\StatamicCacheRequester\Console\Commands;

use Illuminate\Console\Command;
use Artisan;

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
        $connection = config('statamic-cache-requester.queue_connection');
        $queue = config('statamic-cache-requester.queue_name');

        try {
            Artisan::call("queue:clear {$connection} --queue={$queue} --force");
            $this->info("{$connection}:{$queue} queue has been cleared");
        }
        catch(\Throwable $e){
            throw new \Exception('Could not clear cache requester queue.');
        }

        return 0;
    }
}
