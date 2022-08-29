<?php

namespace stuartcusackie\StatamicGlideRequester\Console\Commands;

use Illuminate\Console\Command;
use stuartcusackie\StatamicGlideRequester\StatamicGlideRequester;

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
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        StatamicGlideRequester::queueAllEntries();

        return 0;
    }
}
