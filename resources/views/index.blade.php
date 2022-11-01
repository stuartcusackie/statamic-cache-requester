@extends('statamic::layout')
@section('title', __('Cache Requester'))

@section('content')

    <header class="mb-3">
        @include('statamic::partials.breadcrumb', [
            'url' => cp_route('utilities.index'),
            'title' => __('Utilities')
        ])
        <div class="flex items-center justify-between">
            <h1>{{ __('Cache Requester') }}</h1>
        </div>
    </header>

    <p class="mb-1">Don't forget to set up your config file and make sure your worker is running on the defined queue.<p>

    <div class="flex items-center mb-3"> 
         <div class="mr-2 badge-pill-sm bg-white border mr-1">
            <span class="text-grey-80 font-medium">{{ __('Queue connection') }}:</span> 
            {{ config('statamic-cache-requester.queue_connection') }}
        </div> 

         <div class="mr-2 badge-pill-sm bg-white border">
            <span class="text-grey-80 font-medium">{{ __('Queue name') }}:</span> 
            {{ config('statamic-cache-requester.queue_name') }}
        </div> 
    </div>

    <div class="card p-0">
        <div class="p-2">
            <div class="flex justify-between items-center">
                <div class="pr-4">
                    <h2 class="font-bold">{{ __('Entries') }}</h2>
                    <p class="text-grey text-sm my-1">This will queue up every entry for a front-end load and engage the static cache if enabled. This action will clear the current queue.</p>
                </div>

                <form method="POST" action="{{ cp_route('utilities.cache-requester.process-entries') }}">
                    @csrf
                    <button class="btn">{{ __('Request Entries') }}</button>
                </form>
            </div>
        </div>
        <div class="p-2 bg-grey-20 border-t">
            <div class="flex justify-between items-center">
                <div class="pr-4">
                    <h2 class="font-bold">{{ __('Entries & Images') }}</h2>
                    <p class="text-grey text-sm my-1">This will queue up every entry for a front-end load and engage the static cache if enabled. This will also process any images within the pages that match your configuration. You should only do this when lots of new images are added to multiple entries, or on the launch of a new application. This action will clear the current queue.</p>
                </div>

                <form method="POST" action="{{ cp_route('utilities.cache-requester.process-images') }}" class="mb-6">
                    @csrf
                    <button class="btn">{{ __('Request Images') }}</button>
                </form>
            </div>
        </div>

        <div class="p-2 border-t">
            <div class="flex justify-between items-center">
                <div class="pr-4">
                    <h2 class="font-bold">{{ __('Clear the Queue') }}</h2>
                    <p class="text-grey text-sm my-1">Clear the entire cache requester queue.</p>
                </div>

                <form method="POST" action="{{ cp_route('utilities.cache-requester.clear-queue') }}">
                    @csrf
                    <button class="btn">{{ __('Clear') }}</button>
                </form>
            </div>
        </div>
    </div>
@stop
