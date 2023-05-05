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

        $this->app->singleton(\App\Repositories\MediaOwnerRepository::class, function ($app) {
            return new \App\Repositories\MediaOwnerRepository();
        });
        $this->app->singleton(\App\Repositories\Tag::class, function ($app) {
            return new \App\Repositories\TagRepository();
        });
        $this->app->singleton(\App\Repositories\StatsRepository::class, function ($app) {
            return new \App\Repositories\StatsRepository();
        });

        $this->app->singleton(\App\Repositories\SplurgeAccessTokenRepository::class, function () {
            return new \App\Repositories\SplurgeAccessTokenRepository();
        });

        $this->app->singleton(\App\Repositories\BookingsRepository::class, \App\Repositories\BookingsRepository::class);

        $this->app->singleton(\App\Repositories\CustomerRepository::class, \App\Repositories\CustomerRepository::class);

        $this->app->singleton(\App\Repositories\CommunicationsRepository::class, \App\Repositories\CommunicationsRepository::class);

        $this->app->singleton(\App\Repositories\CustomerEventRepository::class, \App\Repositories\CustomerEventRepository::class);

        $this->app->singleton(\App\Repositories\EventTablesRepository::class, \App\Repositories\EventTablesRepository::class);
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
