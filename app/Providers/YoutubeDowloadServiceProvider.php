<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use YoutubeDl\YoutubeDl;

class YoutubeDowloadServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(YoutubeDl::class, function () {
            return new YoutubeDl();
        });

        $this->app->alias(YoutubeDl::class, 'youtube-dl');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [YoutubeDl::class];
    }
}
