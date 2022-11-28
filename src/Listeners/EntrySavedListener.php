<?php

namespace stuartcusackie\StatamicCacheRequester\Listeners;

use Statamic\Events\EntrySaved;
use Illuminate\Support\Facades\Log;
use stuartcusackie\StatamicCacheRequester\Jobs\RequestUrl;

class EntrySavedListener
{
    public function handle(EntrySaved $event): void
    {
        if($event->entry->url) {
                
            try{
                RequestUrl::dispatch(url($event->entry->url), true);
            }
            catch(\Throwable $e){
                Log::warning('Could not queue saved entry for cache requesting.');
            }
            
        }
    }
}
