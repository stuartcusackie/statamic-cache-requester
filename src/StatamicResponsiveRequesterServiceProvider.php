<?php

namespace stuartcusackie\StatamicResponsiveRequester;

use Illuminate\Support\ServiceProvider;
use stuartcusackie\StatamicResponsiveRequester\Console\Commands\RequestResponsiveImages;

class StatamicResponsiveRequesterServiceProvider extends ServiceProvider
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
                RequestResponsiveImages::class,
            ]);
        }

        return $this;
    }
}