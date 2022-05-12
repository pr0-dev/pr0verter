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
</div>


## Dependencies:
* PHP8.1
* NPM
* Composer
* yt-dl|yt-dlp
* ffmpeg
* any kind of database

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
