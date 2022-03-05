<?php

namespace App\Providers;

use App\Utilities\Youtube;
use Illuminate\Support\ServiceProvider;

class YoutubeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Youtube::class, function () {
            return new Youtube(config('youtube.key'));
        });

        $this->app->alias(Youtube::class, 'youtube');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [Youtube::class];
    }
}
