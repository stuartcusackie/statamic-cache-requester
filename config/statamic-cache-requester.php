<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    |
    | Enable this to scan and request all images for an entry after each save.
    | This can cause the control panel to become sluggish on sites with
    | a lot of imagery.
    */
    'request_on_entry_save' => false,

    /*
    |--------------------------------------------------------------------------
    | Queue
    |--------------------------------------------------------------------------
    |
    | Define your queue settings here.
    |
    */
    'queue' => [
        'connection' => env('STATAMIC_CACHE_REQUESTER_QUEUE_CONNECTION', 'default'),
        'name' => env('STATAMIC_CACHE_REQUESTER_QUEUE_NAME', 'cacherequester')
    ],

    /*
    |--------------------------------------------------------------------------
    | Source attributes
    |--------------------------------------------------------------------------
    |
    | This option defines what html attributes to look for 
    | on each img / picture element. If you are using lazy loading then
    | you might have different attributes than the ones listed below.
    |
    */

    'src_attributes' => [
        'src',
        'lazy-src',
        'srcset',
        'lazy-srcset'
    ],

    /*
    |--------------------------------------------------------------------------
    | Asset views
    |--------------------------------------------------------------------------
    |
    | These options are useful when you are requesting single asset views.
    | For example, a lightbox component might use JS to request a larger
    | size for a responsive image, in which case the values below might be
    |
    | 'post_data_attributes' => [
    |    'data-asset-id',
    | ],
    |    
    | 'asset_view_path' => '/lightbox-image-asset'
    |
    | The script will then search each element for 'data-asset-id'
    | and POST it as data to lightbox-image-asset to return your
    | custom view.
    |
    */

    'post_data_attributes' => [],
        
    'asset_view_path' => false,

    /*
    |--------------------------------------------------------------------------
    | Custom routing
    |--------------------------------------------------------------------------
    |
    | If you are using a custom routing system that uses placeholder slugs for
    | entries in other collections then enter them here and the utility
    | will replace them when requesting urls.
    |
    | The format is as follows:
    | 'slug_replacements' => [
    |      'placeholder' => 'entry_field'
    | ]
    */

    'slug_replacements' => []

];
