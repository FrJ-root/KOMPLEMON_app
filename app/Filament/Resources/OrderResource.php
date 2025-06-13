<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Carbon;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationLabel = 'Commandes';

    protected static ?string $modelLabel = 'Commande';

    protected static ?string $pluralModelLabel = 'Commandes';

    protected static ?string $navigationGroup = 'Gestion des Commandes';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations de la commande')
                    ->schema([
                        Forms\Components\Select::make('client_id')
                            ->relationship('client', 'nom')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Client'),
                            
                        Forms\Components\DateTimePicker::make('date_commande')
                            ->required()
                            ->label('Date de commande'),
                            
                        Forms\Components\Select::make('statut')
                            ->options([
                                'en attente' => 'En attente',
                                'confirmé' => 'Confirmé',
                                'expédié' => 'Expédié',
                                'livré' => 'Livré',
                                'annulé' => 'Annulé',
                            ])
                            ->default('en attente')
                            ->required()
                            ->label('Statut'),
                            
                        Forms\Components\TextInput::make('total')
                            ->numeric()
                            ->prefix('€')
                            ->required()
                            ->label('Total'),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Historique')
                    ->schema([
                        Forms\Components\Textarea::make('historique')
                            ->rows(4)
                            ->label('Historique')
                            ->disabled()
                            ->dehydrated(),
                    ]),
                    
                Forms\Components\Section::make('Articles commandés')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('produit_id')
                                    ->relationship('product', 'nom')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->label('Produit')
                                    ->columnSpan(4),
                                    
                                Forms\Components\TextInput::make('quantite')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1)
                                    ->label('Quantité')
                                    ->columnSpan(1),
                                    
                                Forms\Components\TextInput::make('prix_unitaire')
                                    ->numeric()
                                    ->prefix('€')
                                    ->required()
                                    ->label('Prix unitaire')
                                    ->columnSpan(1),
                            ])
                            ->columns(6)
                            ->itemLabel(fn (array $state): ?string => $state['produit_id'] ? 
                                \App\Models\Product::find($state['produit_id'])->nom . ' (x' . ($state['quantite'] ?? 1) . ')' : null)
                            ->addActionLabel('Ajouter un article')
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->collapseAllAction(
                                fn (Forms\Components\Actions\Action $action) => $action->label('Réduire tous les articles')
                            )
                            ->label('Articles'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Commande #')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('date_commande')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->label('Date'),
                    
                Tables\Columns\TextColumn::make('client.nom')
                    ->searchable()
                    ->label('Client'),
                    
                Tables\Columns\TextColumn::make('client.email')
                    ->searchable()
                    ->label('Email')
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('client.telephone')
                    ->searchable()
                    ->label('Téléphone')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('total')
                    ->money('EUR')
                    ->sortable()
                    ->label('Total'),
                    
                Tables\Columns\BadgeColumn::make('statut')
                    ->colors([
                        'danger' => 'annulé',
                        'warning' => 'en attente',
                        'success' => 'livré',
                        'primary' => 'confirmé',
                        'info' => 'expédié',
                    ])
                    ->label('Statut'),
                    
                Tables\Columns\TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Articles')
                    ->toggleable(),
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
                    
                Filter::make('date_debut')
                    ->form([
                        Forms\Components\DatePicker::make('date_debut')
                            ->label('Date de début'),
                        Forms\Components\DatePicker::make('date_fin')
                            ->label('Date de fin'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_debut'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date_commande', '>=', $date),
                            )
                            ->when(
                                $data['date_fin'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date_commande', '<=', $date),
                            );
                    })
                    ->label('Période'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('changeStatus')
                    ->label('Changer statut')
                    ->icon('heroicon-o-tag')
                    ->form([
                        Forms\Components\Select::make('new_status')
                            ->label('Nouveau statut')
                            ->options([
                                'en attente' => 'En attente',
                                'confirmé' => 'Confirmé',
                                'expédié' => 'Expédié',
                                'livré' => 'Livré',
                                'annulé' => 'Annulé',
                            ])
                            ->required(),
                    ])
                    ->action(function (Order $record, array $data): void {
                        $record->updateStatus($data['new_status'], auth()->user()->name);
                    })
                    ->color('warning')
                    ->modalHeading('Changer le statut de la commande')
                    ->modalDescription('Cette action sera enregistrée dans l\'historique de la commande.')
                    ->modalSubmitActionLabel('Confirmer'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('exportOrders')
                        ->label('Exporter')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (Collection $records) {
                            return Excel::download(new OrdersExport($records), 'commandes_' . now()->format('Y-m-d') . '.xlsx');
                        })
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('changeStatusBulk')
                        ->label('Changer statut')
                        ->icon('heroicon-o-tag')
                        ->form([
                            Forms\Components\Select::make('new_status')
                                ->label('Nouveau statut')
                                ->options([
                                    'en attente' => 'En attente',
                                    'confirmé' => 'Confirmé',
                                    'expédié' => 'Expédié',
                                    'livré' => 'Livré',
                                    'annulé' => 'Annulé',
                                ])
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            foreach ($records as $record) {
                                $record->updateStatus($data['new_status'], auth()->user()->name);
                            }
                        }),
                ]),
            ]);
    }
    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informations de la commande')
                    ->schema([
                        Infolists\Components\TextEntry::make('id')
                            ->label('Commande #'),
                            
                        Infolists\Components\TextEntry::make('date_commande')
                            ->dateTime('d/m/Y H:i')
                            ->label('Date de commande'),
                            
                        Infolists\Components\TextEntry::make('statut')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'en attente' => 'warning',
                                'confirmé' => 'primary',
                                'expédié' => 'info',
                                'livré' => 'success',
                                'annulé' => 'danger',
                                default => 'gray',
                            })
                            ->label('Statut'),
                            
                        Infolists\Components\TextEntry::make('total')
                            ->money('EUR')
                            ->label('Total'),
                    ])
                    ->columns(2),
                    
                Infolists\Components\Section::make('Informations client')
                    ->schema([
                        Infolists\Components\TextEntry::make('client.nom')
                            ->label('Nom'),
                            
                        Infolists\Components\TextEntry::make('client.email')
                            ->label('Email'),
                            
                        Infolists\Components\TextEntry::make('client.telephone')
                            ->label('Téléphone'),
                            
                        Infolists\Components\TextEntry::make('client.adresse')
                            ->label('Adresse')
                            ->columnSpan(2),
                    ])
                    ->columns(2),
                    
                Infolists\Components\Section::make('Articles commandés')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('items')
                            ->schema([
                                Infolists\Components\TextEntry::make('product.nom')
                                    ->label('Produit')
                                    ->columnSpan(2),
                                    
                                Infolists\Components\TextEntry::make('quantite')
                                    ->label('Quantité'),
                                    
                                Infolists\Components\TextEntry::make('prix_unitaire')
                                    ->money('EUR')
                                    ->label('Prix unitaire'),
                                    
                                Infolists\Components\TextEntry::make('total')
                                    ->state(fn ($record): float => $record->quantite * $record->prix_unitaire)
                                    ->money('EUR')
                                    ->label('Total'),
                            ])
                            ->columns(5),
                    ]),
                    
                Infolists\Components\Section::make('Historique des statuts')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('history')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('entry')
                                    ->label('')
                                    ->columnSpan(1),
                            ])
                            ->grid(1)
                            ->state(function (Order $record): array {
                                $entries = $record->getHistoryEntries();
                                return array_map(function ($entry) {
                                    return ['entry' => $entry];
                                }, $entries);
                            }),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('statut', 'en attente')->count() ?: null;
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
    
    public static function canAccess(): bool
    {
        return auth()->user()->role === 'administrateur' || 
               auth()->user()->role === 'gestionnaire_commandes' ||
               auth()->user()->hasPermission('manage_orders');
    }
}
