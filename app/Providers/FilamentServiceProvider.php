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
            // Register navigation groups
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label('E-commerce')
                    ->icon('heroicon-o-shopping-cart'),
                
                NavigationGroup::make()
                    ->label('Content')
                    ->icon('heroicon-o-document-text'),
                
                NavigationGroup::make()
                    ->label('Settings')
                    ->icon('heroicon-o-cog')
                    ->collapsed(),
            ]);

            // Customize logo and branding
            Filament::registerStyles([
                asset('css/admin-theme.css'),
            ]);

            // Register resources based on user permissions
            $user = auth()->user();
            
            if ($user && $user->hasRole('administrateur')) {
                // Admin has access to everything
            } elseif ($user && $user->hasRole('gestionnaire_produits')) {
                // Product manager has access to products and categories
                Filament::registerResources([
                    ProductResource::class,
                    CategoryResource::class,
                ]);
            } elseif ($user && $user->hasRole('gestionnaire_commandes')) {
                // Order manager has access to orders and customers
                Filament::registerResources([
                    OrderResource::class,
                    CustomerResource::class,
                ]);
            } elseif ($user && $user->hasRole('editeur_contenu')) {
                // Content editor has access to blog and testimonials
                Filament::registerResources([
                    BlogPostResource::class,
                    TestimonialResource::class,
                ]);
            }
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
