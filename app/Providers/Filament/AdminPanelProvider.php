<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Illuminate\Http\Request;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\NavigationBuilder;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider{

    public function panel(Panel $panel): Panel{
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('KOMPLEMON admin panel')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                StartSession::class,
                EncryptCookies::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                AddQueuedCookiesToResponse::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigation(function (NavigationBuilder $navigation): NavigationBuilder {
                return $navigation
                    ->items([
                        NavigationItem::make('Voir statistique')
                            ->icon('heroicon-o-chart-bar')
                            ->activeIcon('heroicon-s-chart-bar')
                            ->url(route('filament.admin.pages.dashboard'))
                            ->sort(1),
                    ])
                    ->groups([
                        NavigationGroup::make()
                            ->items([
                                NavigationItem::make('Gerer les coupons')
                                    ->icon('heroicon-o-ticket')
                                    ->activeIcon('heroicon-s-ticket')
                                    ->url(route('filament.admin.resources.coupons.index'))
                                    ->sort(2),
                                
                                NavigationItem::make('Gestion users')
                                    ->icon('heroicon-o-users')
                                    ->activeIcon('heroicon-s-users')
                                    ->url(route('filament.admin.resources.users.index'))
                                    ->sort(3),
                                
                                NavigationItem::make('Settings')
                                    ->icon('heroicon-o-cog-6-tooth')
                                    ->activeIcon('heroicon-s-cog-6-tooth')
                                    ->url(route('filament.admin.pages.settings'))
                                    ->sort(4),
                            ]),
                    ]);
            });
    }
    
}