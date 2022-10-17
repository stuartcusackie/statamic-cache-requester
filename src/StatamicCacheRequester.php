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
                RequestUrl::dispatch(url($entry->url));
            }

        }
    }

    /**
     * Request
     * 
     * @return void
     */
    public static function queueAllImages() {

        self::clearQueue();

        foreach(Entry::all() as $entry) {

            if($entry->url) {
                RequestUrl::dispatch(url($entry->url), true);
            }
        }
    }

    /**
     * Request
     * 
     * @return void
     */
    public static function clearQueue() {

        Artisan::call('queue:clear', ['connection' => 'redis',  '--queue' => 'cacherequester', '--force' => true]);

    }

}