<?php

namespace stuartcusackie\StatamicGlideRequester;

use Statamic\Facades\Entry;
use stuartcusackie\StatamicGlideRequester\Jobs\QueueGlideSources;
use Artisan;

class StatamicGlideRequester {

    /**
     * Dispatch a job 
     * 
     * @param string $url
     * @return void
     */
    public static function queueUrl($url) {
        QueueGlideSources::dispatch($url);
    }

    /**
     * Request
     * 
     * @return void
     */
    public static function queueAllEntries() {

        Artisan::call('queue:clear', ['connection' => 'redis',  '--queue' => 'gliderequester', '--force' => true]);

        foreach(Entry::all() as $entry) {

            if($entry->url) {
                QueueGlideSources::dispatch(url($entry->url));
            }
        }
    }

}