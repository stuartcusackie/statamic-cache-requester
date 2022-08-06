# Statamic Glide Image Requester

This package searches images and picture sources in your Statamic Entries and adds each image url to a Redis queue. The queue can then be processed to retrieve each image url and initiate Glide generation.

The purpose of this package is to pre-generate all website images, and alleviate some of the pressure that image-heavy websites put on the server. You will usually only run the commands provided by this package once on initial deployment of the site, or after any major restructing of asset filenames and folders.


## Installation

```
composer require stuartcusackie/statamic-glide-requester
```

## Requirements

This package utilises a Redis queue called **responsive**. You must have Redis installed on your server.


## Usage

This package provides an artisan command that be used like so

`php artisan glide:requester`

The queue can be ran manually with this command:

`php artisan queue:work redis --queue=gliderequester`
