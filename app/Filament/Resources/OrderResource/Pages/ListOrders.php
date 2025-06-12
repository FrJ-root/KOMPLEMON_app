<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('export_all')
                ->label('Exporter tout')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    return Excel::download(
                        new OrdersExport(static::$resource::getEloquentQuery()->get()),
                        'toutes_les_commandes.xlsx'
                    );
                })
                ->color('success')
                ->visible(fn () => auth()->user()->hasPermission('export_orders')),
        ];
    }
}
