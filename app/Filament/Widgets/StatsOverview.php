<?php

namespace App\Filament\Widgets;

use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $totalSales = Order::where('statut', 'confirmé')->sum('total') ?? 0;
        $totalOrders = Order::count() ?? 0;
        $pendingOrders = Order::where('statut', 'en attente')->count() ?? 0;
        $totalCustomers = Customer::count() ?? 0;
        $totalUsers = User::count() ?? 0;
        $activeCoupons = Coupon::where('is_active', true)
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })->count() ?? 0;
        
        return [
            Stat::make('Total Sales', '€' . number_format($totalSales, 2))
                ->description('Overall revenue')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            
            Stat::make('Orders', $totalOrders)
                ->description('Pending: ' . $pendingOrders)
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('warning'),
            
            Stat::make('Customers', $totalCustomers)
                ->description('Registered users')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
                
            Stat::make('Admin Users', $totalUsers)
                ->description('Backend users')
                ->descriptionIcon('heroicon-m-key')
                ->color('secondary'),
                
            Stat::make('Active Coupons', $activeCoupons)
                ->description('Available for use')
                ->descriptionIcon('heroicon-m-ticket')
                ->color('success'),
        ];
    }
}
