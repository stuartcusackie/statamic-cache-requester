@extends('statamic::layout')
@section('title', __('Glide Requester'))

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
            <p class="mb-2">Click the button to queue all entries for image processing. You should only do this when lots of new images are added to multiple entries.</p>
            <p>This action will clear the current queue. Don't forget to set up your config file and make sure your redis worker is running on the <strong>gliderequester</strong> queue.</p>
        </div>

        <form method="POST" action="{{ cp_route('utilities.glide-requester.run') }}">
            @csrf
            <button class="btn-primary">{{ __('Request images') }}</button>
        </form>
    </div>
@stop
