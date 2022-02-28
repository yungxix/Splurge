<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SearchProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind("post_search_provider", function ($app) {
            return new \App\Search\PostSearchProvider();
        });

        $this->app->bind("event_search_provider", function ($app) {
            return $app->make('post_search_provider');
        });

        $this->app->bind("gallery_search_provider", function ($app) {
            return new \App\Search\GallerySearchProvider();
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
