<?php

namespace stuartcusackie\StatamicResponsiveRequester\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VisitGlideUrl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The glide image url
     *
     * @var string
     */
    public $url;

    /**
     * Create a new job instance.
     *
     * @param  string  $url
     * @return void
     */
    public function __construct(string $url)
    {
        $this->onConnection('redis');
        $this->onQueue('responsive');
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Http::get($this->url);
    }
}
