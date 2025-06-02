<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\Pages;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Form;
use App\Models\Order;
use Filament\Tables;
use Filament\Forms;

class OrderResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Ventes';
    protected static ?string $model = Order::class;
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Card::make()
                            ->columnSpan(2)
                            ->schema([
                                Select::make('client_id')
                                    ->relationship('customer', 'email')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->label('Client'),
                                    
                                DateTimePicker::make('date_commande')
                                    ->label('Date de commande')
                                    ->required(),
                                    
                                Select::make('statut')
                                    ->label('Statut')
                                    ->options([
                                        'en attente' => 'En attente',
                                        'confirmé' => 'Confirmé',
                                        'expédié' => 'Expédié',
                                        'livré' => 'Livré',
                                        'annulé' => 'Annulé',
                                    ])
                                    ->required(),
                                    
                                TextInput::make('total')
                                    ->label('Total')
                                    ->numeric()
                                    ->prefix('€')
                                    ->disabled()
                                    ->required(),
                            ]),
                            
                        Card::make()
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Créée le')
                                    ->content(fn (Order $record): string => $record->created_at->format('d/m/Y H:i')),
                                    
                                Forms\Components\Placeholder::make('updated_at')
                                    ->label('Dernière mise à jour')
                                    ->content(fn (Order $record): string => $record->updated_at->format('d/m/Y H:i')),
                            ]),
                    ]),
                    
                Section::make('Historique des statuts')
                    ->schema([
                        Repeater::make('historique')
                            ->schema([
                                DateTimePicker::make('date')
                                    ->required(),
                                Select::make('statut')
                                    ->options([
                                        'en attente' => 'En attente',
                                        'confirmé' => 'Confirmé',
                                        'expédié' => 'Expédié',
                                        'livré' => 'Livré',
                                        'annulé' => 'Annulé',
                                    ])
                                    ->required(),
                                TextInput::make('commentaire')
                                    ->maxLength(255),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('date_commande')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('customer.email')
                    ->label('Client')
                    ->searchable(),
                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'en attente' => 'warning',
                        'confirmé' => 'info',
                        'expédié' => 'success',
                        'livré' => 'success',
                        'annulé' => 'danger',
                    })
                    ->sortable(),
                TextColumn::make('total')
                    ->label('Total')
                    ->money('EUR')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('statut')
                    ->options([
                        'en attente' => 'En attente',
                        'confirmé' => 'Confirmé',
                        'expédié' => 'Expédié',
                        'livré' => 'Livré',
                        'annulé' => 'Annulé',
                    ])
                    ->label('Statut'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export')
                        ->label('Exporter (CSV)')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(fn (Collection $records) => self::exportOrders($records)),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
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
    
    protected static function exportOrders($records)
    {
        // Implémentation de l'export CSV
    }
}
