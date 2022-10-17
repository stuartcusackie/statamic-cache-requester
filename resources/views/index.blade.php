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

    <div>
        <div class="mb-4">Don't forget to set up your config file and make sure your redis worker is running on the <strong>cacherequester</strong> queue.</div>

        <div class="mb-2">
            <h2 class="mb-1">Entries</h2>
            <p>Click the button below to queue up all entries for a public url request. This will engage the static cache if enabled. This action will clear the current queue.</p>
        </div>

        <form method="POST" action="{{ cp_route('utilities.cache-requester.process-entries') }}" class="mb-6">
            @csrf
            <button class="btn-primary">{{ __('Request Entries') }}</button>
        </form>

        <div class="mb-2">
            <h2 class="mb-1">Entries &amp; Images</h2>
            <p>Click the button below queue all entries for image processing. This will engage the static cache if enabled and process any glide images within the pages. You should only do this when lots of new images are added to multiple entries. This action will clear the current queue.</p>
        </div>

        <form method="POST" action="{{ cp_route('utilities.cache-requester.process-images') }}" class="mb-6">
            @csrf
            <button class="btn-primary">{{ __('Request Images') }}</button>
        </form>

        <div class="mb-2">
            <h2 class="mb-1">Clear the Queue</h2>
            <p>Click the button below to clear the current request queue.</p>
        </div>

        <form method="POST" action="{{ cp_route('utilities.cache-requester.clear-queue') }}">
            @csrf
            <button class="btn-primary">{{ __('Clear Queue') }}</button>
        </form>
    </div>
@stop
