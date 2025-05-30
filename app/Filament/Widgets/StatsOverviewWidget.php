<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\Client;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    
    // Set a fixed height to avoid layout issues
    protected int | string | array $columnSpan = 'full';
    
    protected function getStats(): array
    {
        return [
            Stat::make('Produits', Product::count() ?? 0)
                ->description('Total des produits')
                ->color('success'),
                
            Stat::make('Commandes', Order::count() ?? 0)
                ->description('Total des commandes')
                ->color('warning'),
                
            Stat::make('Clients', Client::count() ?? 0)
                ->description('Total des clients')
                ->color('info'),
                
            Stat::make('Bienvenue', 'KOMPLEMON Admin Panel')
                ->description('Administration du site KOMPLEMON')
                ->color('success'),
        ];
    }
}
