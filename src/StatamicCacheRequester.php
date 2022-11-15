<?php

namespace stuartcusackie\StatamicCacheRequester;

use Statamic\Facades\Entry;
use stuartcusackie\StatamicCacheRequester\Jobs\RequestUrl;
use Artisan;

class StatamicCacheRequester {

    /**
     * Request
     * 
     * @return void
     */
    public static function queueAllEntries() {

        self::clearQueue();

        foreach(Entry::all() as $entry) {

            if($entry->url) {

                try {
                    RequestUrl::dispatch(url($entry->url));
                }
                catch(\Throwable $e){
                    throw new \Exception('Could not queue an entry for cache requesting.');
                }

            }

        }
    }



}