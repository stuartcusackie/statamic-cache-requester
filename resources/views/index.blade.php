@extends('statamic::layout')
@section('title', __('Rebuild Search'))

@section('content')

    <header class="mb-3">
        @include('statamic::partials.breadcrumb', [
            'url' => cp_route('utilities.index'),
            'title' => __('Utilities')
        ])
        <div class="flex items-center justify-between">
            <h1>{{ __('Glide Requester') }}</h1>
        </div>
    </header>

    <div>
        <div class="mb-4">
            <p class="mb-2">Click the button to run the artisan command and it will search for all pictures and image elements in your entries and assets and queue them up for retrieval. This should greatly reduce initial page load times when lots of new images have been added.</p>
            <p>Don't forget to set up your config file and make sure your redis worker is running on the <strong>gliderequester</strong> queue.</p>
        </div>

        <form method="POST" action="{{ cp_route('utilities.glide-requester.run') }}">
            @csrf
            <button class="btn-primary">{{ __('Request images') }}</button>
        </form>
    </div>
@stop
