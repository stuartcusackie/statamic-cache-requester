<?php

namespace stuartcusackie\StatamicGlideRequester;

use Illuminate\Support\ServiceProvider;
use stuartcusackie\StatamicGlideRequester\Console\Commands\RequestGlideImages;

class StatamicGlideRequesterServiceProvider extends ServiceProvider
{   

    public function boot()
    {
        $this
            ->registerCommands();
    }

    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                RequestGlideImages::class,
            ]);
        }

        return $this;
    }
}
