# Statamic Cache Requester

This Statamic utility queues up all entry urls for retrieval, which engages the caches and makes first time loads much quicker. 

The utility can also search for all image and picture sources within your entries and queue them up for a separate retrieval and initiate Glide generation. This is particularly useful when you have lots of responsive image variants or if you are using Spatie's Statamic Responsive Images package. This can fix server crashes for image heavy websites where Glide has a lot of processing to perform.

The package also listens for EntrySaved events and automatically queues the entry url for image requests.

**CAUTION:** Glide image manipulation can take a lot of work, especially when using responsive image variants and jpeg fallbacks. For example a site using Spatie's responsive images addon could have 10 sizes and 2 formats per image. If this site has 1000 images then 20,000 variants will need to be manipulated by Glide. Keep an eye on your CPU usage, especially if using a hosting server that limits CPU (e.g. AWS-EC2).


## Installation

```
composer require stuartcusackie/statamic-cache-requester
```

```
php please vendor:publish --tag=statamic-cache-requester-config
```

Check the config file for special features such as queue configuration and lightbox image request generation.


## Commands

```
php artisan requester:entries
```
If you are using Static caching then It's a good idea to add this command to your deploy script if using forge


```
php artisan requester:images
```
You will usually only run this command once on initial deployment of the site, or after any major restructuring of asset filenames and folders.


```
php artisan requester:clear
```
Clears the entry and images queue.


## Queues

By default, this package utilises a Redis queue called **cacherequester**. You can configure the package to use whatever queue and connection that you prefer.

The default queue can be ran manually with this command:

`php artisan queue:work redis --queue=cacherequester`

But you are probably better off using a Laravel Forge worker, or something similar, as workers are prone to exit prematurely when using the command line.
