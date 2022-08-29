<?php

namespace stuartcusackie\StatamicGlideRequester\Http\Controllers;

use Illuminate\Http\Request;
use Statamic\Http\Controllers\Controller;
use Artisan;

class GlideRequesterController extends Controller
{
    /**
     * Show the index utility page
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Request $request)
    {
        return view('statamic-glide-requester::index');
    }

    /**
     * Request the images by running the 
     * artisan command
     *
     * @param Request $request
     * @return redirect
     */
    public function run(Request $request)
    {   
        Artisan::call('queue:clear', ['connection' => 'redis',  '--queue' => 'gliderequester', '--force' => true]);
        Artisan::queue('glide:request')->onConnection('redis')->onQueue('gliderequester');

        return back();
    }
}
