<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
        //
        
        // Load Spatie Permission package once the app is booted
        $this->app->singleton(Role::class, function ($app) {
            return new Role();
        });
        
        $this->app->singleton(Permission::class, function ($app) {
            return new Permission();
        });
    }
}
