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
</div>



## Dependencies:
* PHP 8.1
* NodeJS and NPM
* Composer
* yt-dl|yt-dlp
* ffmpeg
* any kind of database

## Development
pr0verter comes with Laravel Sail development environment, that does mean for development and testing you can ignore above requirements for dependencies and just use Laravel Sail instead as long as you have Docker installed on your system.

Since [Laravel Sail](https://laravel.com/docs/8.x/sail) is being installed using [Composer](https://getcomposer.org/) it is required that you do install all dependencies before using [Laravel Sail](https://laravel.com/docs/8.x/sail) to run the development environment. Since [Laravel Sail](https://laravel.com/docs/8.x/sail) does require [Docker](https://www.docker.com/) to be installed we can use an intermediade container to do so as [described in the documentation on how to install dependencies for existing projects](https://laravel.com/docs/8.x/sail#installing-composer-dependencies-for-existing-projects) - this will install all dependencies using [Composer](https://getcomposer.org/) and [NPM](https://www.npmjs.com/).
```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

## Installation:
* Clone the Project
* `composer install --no-dev`
* `npm install`
* `npm run prod`
* `cp .env.example .env`
* Adjust .env to your needs
* `php artisan key:generate`
* `php artisan migrate`
* Setup Supervisor or any other tool to work on the queue.
* Setup crontab or windows Taskscheduler to run `php artisan schedule:run` every minute
