<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    
    protected static ?string $navigationGroup = 'Sales Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('client_id') // Changed from customer_id
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->required(),
                    
                Forms\Components\TextInput::make('order_number')
                    ->required()
                    ->maxLength(255)
                    ->default(fn () => 'ORD-' . strtoupper(substr(md5(microtime()), 0, 8))),
                    
                Forms\Components\Select::make('statut') // Changed from status
                    ->options([
                        'en attente' => 'En attente',
                        'confirmé' => 'Confirmé',
                        'expédié' => 'Expédié',
                        'livré' => 'Livré',
                        'annulé' => 'Annulé',
                    ])
                    ->required(),
                    
                Forms\Components\TextInput::make('total') // Changed from total_amount
                    ->required()
                    ->numeric()
                    ->prefix('€'),
                    
                Forms\Components\Textarea::make('shipping_address')
                    ->required()
                    ->columnSpanFull(),
                    
                Forms\Components\Textarea::make('billing_address')
                    ->columnSpanFull(),
                    
                Forms\Components\Select::make('payment_method')
                    ->options([
                        'credit_card' => 'Credit Card',
                        'paypal' => 'PayPal',
                        'bank_transfer' => 'Bank Transfer',
                    ])
                    ->required(),
                    
                Forms\Components\Select::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'refunded' => 'Refunded',
                    ])
                    ->required(),
                    
                Forms\Components\Textarea::make('historique') // History field
                    ->columnSpanFull(),
                    
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('customer.name')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('total') // Changed from total_amount
                    ->money('EUR')
                    ->sortable(),
                    
                Tables\Columns\SelectColumn::make('statut') // Changed from status
                    ->options([
                        'en attente' => 'En attente',
                        'confirmé' => 'Confirmé',
                        'expédié' => 'Expédié',
                        'livré' => 'Livré',
                        'annulé' => 'Annulé',
                    ])
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('date_commande') // Order date instead of created_at
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('statut') // Changed from status
                    ->options([
                        'en attente' => 'En attente',
                        'confirmé' => 'Confirmé',
                        'expédié' => 'Expédié',
                        'livré' => 'Livré',
                        'annulé' => 'Annulé',
                    ]),
                    
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('exportToCsv')
                        ->label('Export to CSV')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(fn (Collection $records) => redirect()->route('orders.export', ['format' => 'csv']))
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            OrderResource\RelationManagers\ItemsRelationManager::class,
            OrderResource\RelationManagers\StatusHistoryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
