<?php

namespace stuartcusackie\StatamicCacheRequester;

use Statamic\Providers\AddonServiceProvider;
use Statamic\Facades\Utility;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;
use Statamic\Events\EntrySaved;
use Illuminate\Support\Facades\Log;
use stuartcusackie\StatamicCacheRequester\Http\Controllers\CacheRequesterController;
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
            ->listen()
            ->makeUtility();

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
                catch(\RedisException $e){
                    Log::warning('Redis Error: Could not queue saved entry for image requesting.');
                }
                
            }

        });

        return $this;

    }

    protected function makeUtility() {

        Utility::make('cache-requester')
            ->title('Cache Requester')
            ->navTitle('Requester')
            ->description('Engages caches for all entries and queues up images for glide generation.')
            ->routes(function (Router $router) {
                $router->get('/', [CacheRequesterController::class, 'show'])->name('show');
                $router->post('/process-entries', [CacheRequesterController::class, 'processEntries'])->name('process-entries');
                $router->post('/process-images', [CacheRequesterController::class, 'processImages'])->name('process-images');
                $router->post('/clear-queue', [CacheRequesterController::class, 'clearQueue'])->name('clear-queue');
            })
            ->register();

    }
}
