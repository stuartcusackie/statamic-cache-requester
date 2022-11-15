<?php

namespace stuartcusackie\StatamicCacheRequester;

use Statamic\Providers\AddonServiceProvider;
use Statamic\Facades\Utility;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;
use Statamic\Events\EntrySaved;
use Illuminate\Support\Facades\Log;
use stuartcusackie\StatamicCacheRequester\Jobs\RequestUrl;
use stuartcusackie\StatamicCacheRequester\Console\Commands\RequestEntries;
use stuartcusackie\StatamicCacheRequester\Console\Commands\RequestImages;
use stuartcusackie\StatamicCacheRequester\Console\Commands\ClearRequestQueue;

class ServiceProvider extends AddonServiceProvider
{   

    public function bootAddon()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'statamic-cache-requester');

        $this
            ->registerCommands()
            ->listen();

    }

    protected function registerCommands()
    {
        $this->commands([
            RequestEntries::class,
            RequestImages::class,
            ClearRequestQueue::class,
        ]);

        return $this;
    }

    protected function listen() {

        Event::listen(function (EntrySaved $event) {

            if($event->entry->url) {
                
                try{
                    RequestUrl::dispatch(url($event->entry->url), true);
                }
                catch(\Throwable $e){
                    Log::warning('Could not queue saved entry for cache requesting.');
                }
                
            }

        });

        return $this;

    }
}
