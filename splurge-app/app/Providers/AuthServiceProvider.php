<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\CustomerEvent::class => \App\Policies\CustomerEventPolicy::class,
        \App\Models\CustomerEventGuest::class => \App\Policies\CustomerEventGuestPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->defineGates();
    }

    private function defineGates() {
        Gate::before(function ($user, $ability) {
            if ($user->hasAnyRole(['super', 'system'])) {
                return true;
            }
        });
        Gate::define('admin', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('system', function ($user) {
            return $user->hasRole('system');
        });

        Gate::define('verify-guests', function (User $user) {
            return $user->hasAnyRole(['view-customer-events', 'manage-customer-events', 'verify-guests']);
        });
    }
}
