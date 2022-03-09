<?php

namespace App\Providers;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('app.env', 'local') != 'local') {
            $this->app->bind('path.public', function () {
                return base_path() . '/../dev';
            });
        }
        if (config('logging.log_queries')) {
            DB::listen(function (QueryExecuted $query) {
                Log::debug("[Query] {$query->sql}", [
                    'execute_time_milliseconds' => $query->time,
                    'params' => $query->bindings,
                ]);
            });
        }
    }
}
