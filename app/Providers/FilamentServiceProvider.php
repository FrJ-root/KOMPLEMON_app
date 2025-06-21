<?php

namespace App\Providers;

use App\Filament\Resources\BlogPostResource;
use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\CouponResource;
use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\OrderResource;
use App\Filament\Resources\ProductResource;
use App\Filament\Resources\PromotionResource;
use App\Filament\Resources\TestimonialResource;
use App\Filament\Resources\UserResource;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Filament::serving(function () {
            $user = auth()->user();
            
            // For all roles - hide unnecessary resources
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label('E-commerce')
                    ->icon('heroicon-o-shopping-bag'),
                NavigationGroup::make()
                    ->label('Content')
                    ->icon('heroicon-o-document-text'),
                NavigationGroup::make()
                    ->label('Administration')
                    ->icon('heroicon-o-cog'),
            ]);
            
            // Administrators see everything
            if ($user && $user->hasRole('administrateur')) {
                // No need to hide any resources for admins
                return;
            }
            
            // Product Managers see only product-related resources
            if ($user && $user->hasRole('gestionnaire_produits')) {
                Filament::registerNavigationItems([
                    NavigationItem::make('Dashboard')
                        ->icon('heroicon-o-home')
                        ->activeIcon('heroicon-s-home')
                        ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.dashboard'))
                        ->sort(-2)
                        ->url(route('filament.admin.pages.dashboard')),
                ]);
                
                // Hide all navigation groups except E-commerce
                $hiddenGroups = ['Content', 'Administration'];
                foreach ($hiddenGroups as $group) {
                    Filament::getNavigationGroups()->first(fn ($item) => $item->getLabel() === $group)?->hidden();
                }
                
                // Hide resources not related to products, categories, and media
                $allowedResources = [
                    'App\\Filament\\Resources\\ProductResource',
                    'App\\Filament\\Resources\\CategoryResource',
                    'App\\Filament\\Resources\\MediaResource',
                ];
                
                Filament::getNavigationItems()
                    ->filter(function ($item) use ($allowedResources) {
                        // Keep dashboard and explicitly allowed resources
                        if ($item->getLabel() === 'Dashboard') {
                            return true;
                        }
                        
                        // Check if the item belongs to an allowed resource
                        $activeItem = $item->getActiveItem();
                        if ($activeItem) {
                            $activeItemUrl = $activeItem->getUrl();
                            foreach ($allowedResources as $resource) {
                                if (strpos($activeItemUrl, strtolower(class_basename($resource))) !== false) {
                                    return true;
                                }
                            }
                        }
                        
                        return false;
                    });
            }
            
            // Order Manager logic would go here
            
            // Content Editor logic would go here
        });

        // Override Filament routes with custom controllers if needed
        Route::middleware('web')->group(function () {
            Route::get('/admin/login', [LoginController::class, 'showLoginForm'])
                ->name('filament.admin.auth.login');
            
            Route::post('/admin/login', [LoginController::class, 'login'])
                ->name('filament.admin.auth.login.attempt');
        });
    }
}
