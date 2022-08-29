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
    | Asset view path
    |--------------------------------------------------------------------------
    |
    | This option is useful when you are requesting single asset views.
    | For example, a lightbox component might request a larger
    | size for a responsive image, in which case the value below
    | might be '/lightbox-image-asset'.
    |
    | This feature assumes you are POSTing the asset id to the url.
    |
    */
        
    'asset_view_path' => false

];