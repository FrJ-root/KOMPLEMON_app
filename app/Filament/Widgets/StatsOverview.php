<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalSales = Order::where('statut', '!=', 'annulé')->sum('total');
        $totalOrders = Order::count();
        $pendingOrders = Order::where('statut', 'en attente')->count();
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();
        
        $ordersThisMonth = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('statut', '!=', 'annulé')
            ->count();
            
        $ordersLastMonth = Order::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->where('statut', '!=', 'annulé')
            ->count();
            
        $salesThisMonth = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('statut', '!=', 'annulé')
            ->sum('total');
        
        $percentChange = $ordersLastMonth > 0 
            ? round((($ordersThisMonth - $ordersLastMonth) / $ordersLastMonth) * 100, 2) 
            : 0;
            
        return [
            Stat::make('Ventes totales', number_format($totalSales, 2) . ' €')
                ->description('Chiffre d\'affaires global')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('success'),
                
            Stat::make('Commandes', $totalOrders)
                ->description($pendingOrders . ' en attente')
                ->descriptionIcon('heroicon-m-truck')
                ->color('primary'),
                
            Stat::make('Commandes ce mois', $ordersThisMonth)
                ->description($percentChange >= 0 ? '+' . $percentChange . '%' : $percentChange . '%')
                ->descriptionIcon($percentChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($percentChange >= 0 ? 'success' : 'danger'),
                
            Stat::make('Clients', $totalCustomers)
                ->description('Base clientèle')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),
                
            Stat::make('Produits', $totalProducts)
                ->description('Catalogue complet')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),
        ];
    }
}
