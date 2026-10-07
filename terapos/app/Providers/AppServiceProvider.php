<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;

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
    { {
            Gate::define('edit-faktur', function ($user) {
                return $user->role === 'owner';
            });

            Gate::define('delete-faktur', function ($user) {
                return $user->role === 'owner';
            });
            Paginator::useBootstrapFive();
        }
    }
}
