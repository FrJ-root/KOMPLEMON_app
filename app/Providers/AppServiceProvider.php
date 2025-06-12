<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Filament\Facades\Filament;

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
        
        Filament::serving(function () {
            // Customize Filament here if needed
        });
        
        // Improve performance by limiting relation count queries
        \Illuminate\Database\Eloquent\Builder::macro('withExists', function ($relations) {
            foreach ($relations as $relation) {
                $this->withExists($relation);
            }
            
            return $this;
        });
        
        // Add query timeouts to prevent long-running queries
        if (!app()->runningInConsole()) {
            \DB::connection()->setQueryGrammar(new class extends \Illuminate\Database\Query\Grammars\MySqlGrammar {
                public function compileSelect(\Illuminate\Database\Query\Builder $query)
                {
                    return parent::compileSelect($query) . ' /*+ MAX_EXECUTION_TIME(3000) */';
                }
            });
        }
    }
}
