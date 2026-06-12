<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Media;
use App\Observers\MediaObserver;

class MediaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Media::observe(MediaObserver::class);
    }
}
