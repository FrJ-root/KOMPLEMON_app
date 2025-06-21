<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Product;

class ProductStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total des produits', Product::count())
                ->description('Nombre total de produits')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('primary'),
            
            Stat::make('Produits publiés', Product::where('statut', 'publié')->count())
                ->description('Produits visibles sur le site')
                ->descriptionIcon('heroicon-m-eye')
                ->chart([15, 4, 10, 2, 12, 4, 12])
                ->color('success'),
            
            Stat::make('Stock faible', Product::where('stock', '<=', 5)->count())
                ->description('Produits à réapprovisionner')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->chart([2, 4, 7, 2, 10, 3, 5])
                ->color('danger'),
        ];
    }
}
