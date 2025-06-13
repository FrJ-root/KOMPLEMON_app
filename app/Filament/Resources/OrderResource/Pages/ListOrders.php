<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Exports\OrdersExport;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('export')
                ->label('Exporter')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function (array $data) {
                    $query = static::getResource()::getEloquentQuery();
                    
                    // Apply any active filters from the table
                    $tableFiler = $this->getTableFiltersForm();
                    if (!$tableFiler->isHidden()) {
                        $filterState = $tableFiler->getRawState();
                        $this->applyFiltersToTableQuery($query, $filterState);
                    }
                    
                    // Apply search query if present
                    if ($this->getTableSearch()) {
                        $this->applySearchToTableQuery($query, $this->getTableSearch());
                    }
                    
                    $query->with(['client', 'items.product']);
                    
                    return Excel::download(
                        new OrdersExport($query->get()),
                        'commandes_' . now()->format('Y-m-d') . '.xlsx'
                    );
                }),
        ];
    }

    protected function getTableFiltersFormWidth(): string
    {
        return '2xl';
    }

    protected function getTableFiltersFormColumns(): int
    {
        return 3;
    }
}
