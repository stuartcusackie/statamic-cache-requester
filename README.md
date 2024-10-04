# Statamic Cache Requester

Artisan commands that queue up entry and glide urls for retrieval, which engages the caches and makes first time loads much quicker. Note that this package provides no benefict to Statamic applications that save cached images, i.e. cache => true.

This package can do a few things:
1. Queue up all entry urls for retrieval which automatically engages the static cache, making first time loads quicker. UPDATE: Statamic actually has a built in function for this which should be preferable - `static:warm`
2. Search for all image and picture sources within your entries and queue them up for a separate retrieval to initiate Glide generation. This means that all images and responsive variants will be pre-generated for your first visitor. This is particularly useful when you have lots of responsive image variants or if you are using Spatie's Statamic Responsive Images package. It can help to avoid server crashes for image heavy websites where Glide has a lot of processing to perform.
3. Listens for EntrySaved events and automatically queues the entry's url for image requests.

**CAUTION:** Glide image manipulation can take a lot of work, especially when using responsive image variants and jpeg fallbacks. For example, a site using Spatie's responsive images addon could have 10 sizes and 2 formats per image. If this site has 1000 images then 20,000 variants will need to be manipulated by Glide. Keep an eye on your CPU usage, especially if using a hosting server that limits CPU (e.g. AWS-EC2).


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
If you are using Static caching then **it's a good idea to add this command to your deploy script (e.g. Forge).** Deployments clear the static cache so this will rebuild it for all entries afterwards.
<br/><br/>

```
php artisan requester:images
```
You will usually only run this command once on initial deployment of the site, or after any major restructuring of asset filenames and folders. I do **not** recommend adding this to your deployment script as images rarely change.
<br/><br/>

```
php artisan requester:clear
```
Clears the configured queue. Be careful if using the default queue or a shared queue as all jobs will be removed.
<br/><br/>

## Queues

This package utilises a 'cacherequester' queue on the default connection. You can update the queue name using the STATAMIC_CACHE_REQUESTER_QUEUE_NAME env variable.

## Todo

- Add terms to entry requests
- Add multisite functionality
- Experiment with EntryCreated events to process images
