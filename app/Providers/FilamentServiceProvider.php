<?php

namespace App\Providers;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

class FilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Filament::serving(function () {
            // Create default roles if not exists
            if (Role::count() === 0) {
                Role::create(['name' => 'super_admin']);
                Role::create(['name' => 'product_manager']);
                Role::create(['name' => 'order_manager']);
                Role::create(['name' => 'content_editor']);
            }
            
            Filament::registerUserMenuItems([
                UserMenuItem::make()
                    ->label('Manage Users')
                    ->url(UserResource::getUrl())
                    ->icon('heroicon-o-users')
                    ->visible(fn (): bool => auth()->user()->hasRole('super_admin')),
            ]);
        });
    }
}
