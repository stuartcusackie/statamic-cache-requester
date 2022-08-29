# Statamic Glide Image Requester

This Statamic utility searches images and picture sources in your Statamic Entries and adds each url to a Redis queue. The queue can then be processed to retrieve each url and initiate Glide generation.

The purpose of this package is to pre-generate all website images, and alleviate some of the pressure that image-heavy websites put on the server. You will usually only run these commands once on initial deployment of the site, or after any major restructuring of asset filenames and folders.


## Installation

```
composer require stuartcusackie/statamic-glide-requester

```

```
php please vendor:publish --tag=statamic-glide-requester-config
```

Check the config file for special features.

## Requirements

This package utilises a Redis queue called **gliderequester**. You must have Redis installed on your server.

## Usage

This package provides an artisan command that be used like so

`php artisan glide:request`

The queue can be ran manually with this command:

`php artisan queue:work redis --queue=gliderequester`

But you are probably better off using a Laravel Forge worker, or something similar, as workers are prone to exit prematurely when using the command line.

## Improvements

- Add more detail and flash messages to utility view.
- Add entry saved event listener to automatically queue up image retrieval for a single url.
