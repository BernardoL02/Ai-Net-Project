<?php

namespace App\Providers;

use App\Policies\DashboardPolicy;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('type', function (User $user) {

            return $user->type === 'A' || $user->type === 'E';
        });

        Gate::define('profile', function (User $user) {

            return $user->type === 'A' || $user->type === 'C';
        });
    }
}
