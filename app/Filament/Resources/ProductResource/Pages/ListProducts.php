<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus')
                ->label('Nouveau produit')
                ->color('primary'),
        ];
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            ProductResource\Widgets\ProductStats::class,
        ];
    }
}
