<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget\Stat; 
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class OrdersOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $pendingOrders = Order::where('statut', 'en attente')->count();
        $totalOrders = Order::count();
        $totalSales = Order::sum('total');
        
        $monthlyRevenue = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');
            
        $lastMonthRevenue = Order::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total');
            
        $revenueChange = $lastMonthRevenue > 0 
            ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 2) 
            : 100;
        
        return [
            Stat::make('Commandes en attente', $pendingOrders)
                ->description('Nécessitent une attention')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Total des commandes', $totalOrders)
                ->description('Depuis le début')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),
            Stat::make('Revenus ce mois-ci', number_format($monthlyRevenue, 2) . ' €')
                ->description($revenueChange >= 0 ? '+' . $revenueChange . '% par rapport au mois dernier' : $revenueChange . '% par rapport au mois dernier')
                ->descriptionIcon($revenueChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenueChange >= 0 ? 'success' : 'danger'),
        ];
    }
    
    public static function canView(): bool
    {
        return auth()->user()->hasPermission('manage_orders');
    }
}
