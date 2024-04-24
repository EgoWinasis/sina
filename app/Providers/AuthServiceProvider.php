<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Gate::define('isSuper', function ($user) {
            return $user->role == 'super';
        });
        Gate::define('isAdmin', function ($user) {
            return $user->role == 'admin';
        });
        Gate::define('isKepala', function ($user) {
            return $user->role == 'kepala';
        });
        Gate::define('isUser', function ($user) {
            return $user->role == 'user';
        });
        Gate::define('isMarkom', function ($user) {
            return $user->jabatan == 'Marketing Komunitas';
        });
    }
}
