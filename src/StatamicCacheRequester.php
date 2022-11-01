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

    /**
     * Request
     * 
     * @return void
     */
    public static function queueAllImages() {

        self::clearQueue();

        foreach(Entry::all() as $entry) {

            if($entry->url) {

                try {
                    RequestUrl::dispatch(url($entry->url), true);
                }
                catch(\Throwable $e){
                    throw new \Exception('Could not queue entry for image cache requesting.');
                }

            }
        }
    }

    /**
     * Request
     * 
     * @return void
     */
    public static function clearQueue() {

        $connection = config('statamic-cache-requester.queue_connection');
        $queue = config('statamic-cache-requester.queue_name');

        try {
            Artisan::call("queue:clear {$connection} --queue={$queue} --force");
        }
        catch(\Throwable $e){
            throw new \Exception('Could not clear cache requester queue.');
        }

    }

}