<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Source attributes
    |--------------------------------------------------------------------------
    |
    | This option define what html attributes to look for 
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
    | These options is useful when you are requesting single asset views.
    | For example, a lightbox component might request a larger
    | size for a responsive image, in which case the values below might be
    |
    | 'post_data_attributes' => [
    |    'data-img-id',
    | ],
    |    
    | 'asset_view_path' => '/lightbox-image-asset'
    |
    | This feature assumes you are POSTing the asset id to the url.
    |
    */

    'post_data_attributes' => [],
        
    'asset_view_path' => false

];