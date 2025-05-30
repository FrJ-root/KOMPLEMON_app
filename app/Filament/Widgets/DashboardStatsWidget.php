<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\Client;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Produits', Product::count() ?? 0)
                ->description('Total des produits'),
                
            Stat::make('Commandes', Order::count() ?? 0)
                ->description('Total des commandes'),
                
            Stat::make('Clients', Client::count() ?? 0)
                ->description('Total des clients'),
        ];
    }
}
