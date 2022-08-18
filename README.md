# pr0verter

<div align="center">
  <p align="center">
    <br />
    <img height="150" width="auto" src="https://raw.githubusercontent.com/pr0-dev/pr0verter/master/public/images/pr0verter-260x260.png" />
    <br /><br />
    pr0verter! Video converter for pr0gramm.com
    <br />
    <a href="https://dev.pr0verter.de/"><strong>LIVE URL: dev.pr0verter.de</strong></a>
    <br />
  </p>
  <a href="https://github.com/pr0-dev/pr0verter/actions/workflows/CI.yml" target="_BLANK">
    <img src="https://github.com/pr0-dev/pr0verter/actions/workflows/CI.yml/badge.svg" />
  </a>
  <a href="https://codecov.io/gh/pr0-dev/pr0verter" target="_BLANK">
    <img src="https://codecov.io/gh/pr0-dev/pr0verter/branch/master/graph/badge.svg" />
  </a>
  <a href="https://github.styleci.io/repos/7548986" target="_BLANK">
    <img src="https://github.styleci.io/repos/7548986/shield?style=fl" />
  </a>
</div>



## Dependencies:
* PHP >=8.1
* [Composer](https://getcomposer.org/)
* [NodeJS](https://nodejs.org/en/) and [NPM](https://www.npmjs.com/)
* [yt-dl](https://github.com/ytdl-org/youtube-dl) or [yt-dlp](https://github.com/yt-dlp/yt-dlp)
* [FFmpeg](https://github.com/FFmpeg/FFmpeg) or [FFmpeg for yt-dlp](https://github.com/yt-dlp/FFmpeg-Builds)
* Any SQL database supported by Laravel

## Installation
As a Laravel application pr0verter comes with Laravel Sail development environment, that does mean for development and testing you can ignore mentioned required software and just use the Docker based Laravel Sail environment instead. This does require Docker to be installed on your system! It is highly advised that you read the [Documentation](https://laravel.com/docs/9.x/sail) for Laravel Sail.

### Install dependencies
Since [Laravel Sail](https://laravel.com/docs/9.x/sail) is being installed using [Composer](https://getcomposer.org/) it is required that you do install all dependencies before using [Laravel Sail](https://laravel.com/docs/9.x/sail) to run the development environment. Since [Laravel Sail](https://laravel.com/docs/9.x/sail) does require [Docker](https://www.docker.com/) to be installed we can use an intermediade container to do so as [described in the documentation on how to install dependencies for existing projects](https://laravel.com/docs/8.x/sail#installing-composer-dependencies-for-existing-projects) - this will install all dependencies using [Composer](https://getcomposer.org/) and [NPM](https://www.npmjs.com/).
```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

As all dependencies have been installed using Composer you can start the Laravel Sail development environment using the `sail up` command. 

Once the environment has successfully bootet up you can continue to install and build dependnecies using NPM. You can do this using the following commands:
```
sail npm install \
&& sail npm run dev
```

### Configure
On the first time running the project you will have to create your own `.env` configuration file. **This file will not be commited and is only available locally**. To create it, you can simply create an empty file or copy `.env.example`.

It is required that you do set your application key, this can be generated using the following command:
```
sail artisan key:generate
```

### Database
Once the dependnecies are installed and the application has been configured you can proceed to migrate the database schema. To do so simply run the following command:
```
sail artisan migrate
```

### Production
To run this application in production you can orient yourself at the Laravel Sail Dockerfile and other files in the `/docker` directory for a list of required software and configuration. It is advised that you completely read the [Laravel Documentation](https://laravel.com/docs/9.x/). Keep in mind that Laravel Sail is **NOT** meant to be used in production.