<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentOrdersWidget extends BaseWidget
{
    protected static ?string $heading = 'Commandes récentes';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Commande #'),
                Tables\Columns\TextColumn::make('client.nom')
                    ->label('Client'),
                Tables\Columns\TextColumn::make('date_commande')
                    ->label('Date')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'en attente' => 'warning',
                        'confirmé' => 'info',
                        'expédié' => 'success',
                        'livré' => 'success',
                        'annulé' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('EUR'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (Order $record): string => route('filament.admin.resources.orders.edit', $record))
                    ->icon('heroicon-m-eye'),
            ]);
    }
}
