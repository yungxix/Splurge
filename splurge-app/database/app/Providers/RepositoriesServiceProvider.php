<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\App\Repositories\ServiceRepository::class, function () {
            return new \App\Repositories\ServiceRepository();
        });
        $this->app->singleton(\App\Repositories\GalleryRepository::class, function ($app) {
            return new \App\Repositories\GalleryRepository();
        });
        $this->app->singleton(\App\Repositories\PostRepository::class, function ($app) {
            return new \App\Repositories\PostRepository();
        });
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
}
