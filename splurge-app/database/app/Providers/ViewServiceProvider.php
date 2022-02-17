<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->composeForServices();
        $this->composeForPages();
    }

    private function composeForServices() {
        $repository = $this->app->make(\App\Repositories\ServiceRepository::class);

        View::composer('partials.landing.services', function ($view) use ($repository) {
            $view->with('services', $repository->forWidget());
        });
    }

    private function composeForPages() {
        $repository = $this->app->make(\App\Repositories\PostRepository::class);

        View::composer('partials.landing.pages', function ($view) use ($repository) {
            $view->with('posts', $repository->forWidget());
        });
    }
}
