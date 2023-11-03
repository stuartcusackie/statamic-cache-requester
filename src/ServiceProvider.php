<?php

namespace stuartcusackie\StatamicCacheRequester;

use Statamic\Providers\AddonServiceProvider;
use Statamic\Facades\Utility;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;
use Statamic\Events\EntrySaved;
use stuartcusackie\StatamicCacheRequester\Listeners\EntrySavedListener;
use stuartcusackie\StatamicCacheRequester\Console\Commands\RequestEntries;
use stuartcusackie\StatamicCacheRequester\Console\Commands\RequestImages;
use stuartcusackie\StatamicCacheRequester\Console\Commands\ClearRequestQueue;

class ServiceProvider extends AddonServiceProvider
{   

    public function bootAddon()
    {
        $this
            ->registerCommands()
            ->registerListeners();

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

    protected function registerListeners() {

        if(config('statamic-cache-requester.request_on_entry_save', true)) {
            Event::listen(EntrySaved::class, EntrySavedListener::class);
        }

        return $this;

    }
}
