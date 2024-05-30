<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\CurrentUserRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the CurrentUserRepository
        $this->app->singleton(CurrentUserRepository::class, function ($app) {
            return new CurrentUserRepository();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
