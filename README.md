# Statamic Glide Image Requester

This package searches images and picture sources in your Statamic Entries and adds each url to a Redis queue. The queue can then be processed to retrieve each url and initiate Glide generation.

The purpose of this package is to pre-generate all website images, and alleviate some of the pressure that image-heavy websites put on the server. You will usually only run these commands once on initial deployment of the site, or after any major restructuring of asset filenames and folders.


## Installation

```
composer require stuartcusackie/statamic-glide-requester
```

## Requirements

This package utilises a Redis queue called **gliderequester**. You must have Redis installed on your server.

## Special Notes

If you are using lazy loading and outputting lazy-srcset instead of srcset on your responsive images then this package will handle them. It also works with data-srcset.

## Usage

This package provides an artisan command that be used like so

`php artisan glide:request`

The queue can be ran manually with this command:

`php artisan queue:work redis --queue=gliderequester`

But you are probably better off using a Laravel Forge worker, or something similar, as workers are prone to exit prematurely when using the command line.
