<?php

namespace stuartcusackie\StatamicCacheRequester\Http\Controllers;

use Illuminate\Http\Request;
use Statamic\Http\Controllers\Controller;
use stuartcusackie\StatamicCacheRequester\StatamicCacheRequester;

class CacheRequesterController extends Controller
{
    /**
     * Show the index utility page
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Request $request)
    {
        return view('statamic-cache-requester::index');
    }

    /**
     * Process all entry urls and queue up images
     * for retrieval.
     *
     * @param Request $request
     * @return redirect
     */
    public function processEntries(Request $request)
    {   
        StatamicCacheRequester::queueAllEntries();

        return back()->withSuccess(__('All entry urls have been queued for retrieval.'));
    }

    /**
     * Process all entry urls and queue up images
     * for retrieval.
     *
     * @param Request $request
     * @return redirect
     */
    public function processImages(Request $request)
    {   
        StatamicCacheRequester::queueAllImages();

        return back()->withSuccess(__('All entry urls and images have been queued for retrieval.'));
    }

    /**
     * Clear the cache request queue
     *
     * @param Request $request
     * @return redirect
     */
    public function clearQueue(Request $request)
    {   
        StatamicCacheRequester::clearQueue();

        return back()->withSuccess(__('Cache requester queue has been cleared.'));
    }
}
