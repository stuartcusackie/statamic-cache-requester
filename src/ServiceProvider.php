<?php

namespace stuartcusackie\StatamicGlideRequester;

use Statamic\Providers\AddonServiceProvider;
use Statamic\Facades\Utility;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;
use Statamic\Events\EntrySaved;
use stuartcusackie\StatamicGlideRequester\StatamicGlideRequester;
use stuartcusackie\StatamicGlideRequester\Http\Controllers\GlideRequesterController;
use stuartcusackie\StatamicGlideRequester\Console\Commands\RequestGlideImages;
use Illuminate\Support\Facades\Log;

class ServiceProvider extends AddonServiceProvider
{   

    public function bootAddon()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'statamic-glide-requester');

        $this
            ->registerCommands()
            ->listen()
            ->makeUtility();

    }

    protected function registerCommands()
    {
        $this->commands([
            RequestGlideImages::class,
        ]);

        return $this;
    }

    protected function listen() {

        Event::listen(function (EntrySaved $event) {

            if($event->entry->url) {
                
                try{
                    StatamicGlideRequester::queueUrl(url($event->entry->url));
                }
                catch(\RedisException $e){
                    Log::warning('Redis Error: Could not queue saved entry for glide requesting.');
                }
                
            }

        });

        return $this;

    }

    protected function makeUtility() {

        Utility::make('glide-requester')
            ->title('Glide Requester')
            ->navTitle('Requester')
            ->description('Checks all of your entry and asset urls for glide images and adds them to a queue for generation.')
            ->routes(function (Router $router) {
                $router->get('/', [GlideRequesterController::class, 'show'])->name('show');
                $router->post('/run', [GlideRequesterController::class, 'run'])->name('run');
            })
            ->register();

    }
}
