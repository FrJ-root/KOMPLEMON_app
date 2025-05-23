<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Schema;

class LatestOrders extends BaseWidget
{
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        // Check if the commandes table exists before querying
        if (!Schema::hasTable('commandes')) {
            return $table
                ->query(\Illuminate\Database\Eloquent\Builder::query())
                ->columns([
                    Tables\Columns\TextColumn::make('message')
                        ->default('Orders table not yet available'),
                ]);
        }
        
        // Original query if table exists
        return $table
            ->query(
                Order::query()
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('customer.name')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('EUR'),
                    
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'info',
                        'shipped' => 'success',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                    }),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ]);
    }
}
