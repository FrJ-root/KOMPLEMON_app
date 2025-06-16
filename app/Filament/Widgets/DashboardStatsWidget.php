<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Client;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

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
