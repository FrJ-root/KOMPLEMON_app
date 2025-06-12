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
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationLabel = 'Commandes';

    protected static ?string $navigationGroup = 'Gestion des Commandes';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('statut', 'en attente')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('statut', 'en attente')->count() > 0 ? 'warning' : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('client_id')
                    ->relationship('client', 'nom')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Client'),
                    
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
                    
                Forms\Components\Textarea::make('historique')
                    ->columnSpan(2)
                    ->label('Historique'),
                    
                Forms\Components\Section::make('Détails de la commande')
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
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, callable $set) => 
                                        $set('prix_unitaire', \App\Models\Product::find($state)?->prix ?? 0)),
                                    
                                Forms\Components\TextInput::make('quantite')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->label('Quantité'),
                                    
                                Forms\Components\TextInput::make('prix_unitaire')
                                    ->numeric()
                                    ->prefix('€')
                                    ->required()
                                    ->label('Prix unitaire'),
                            ])
                            ->columns(3)
                    ])
                    ->collapsible()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->label('ID'),
                    
                Tables\Columns\TextColumn::make('client.nom')
                    ->searchable()
                    ->sortable()
                    ->label('Client'),
                    
                Tables\Columns\TextColumn::make('date_commande')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->label('Date'),
                    
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
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Créé le'),
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
                    
                Tables\Filters\DateRangeFilter::make('date_commande')
                    ->label('Période'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Tables\Actions\DeleteAction $action) {
                        if (!Auth::user()->hasPermission('manage_orders')) {
                            Notification::make()
                                ->title('Permission refusée')
                                ->body('Vous n\'avez pas la permission de supprimer des commandes.')
                                ->danger()
                                ->send();
                                
                            $action->cancel();
                        }
                    }),
                Tables\Actions\Action::make('change_status')
                    ->label('Changer le statut')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->form([
                        Forms\Components\Select::make('statut')
                            ->options([
                                'en attente' => 'En attente',
                                'confirmé' => 'Confirmé',
                                'expédié' => 'Expédié',
                                'livré' => 'Livré',
                                'annulé' => 'Annulé',
                            ])
                            ->required()
                            ->label('Nouveau statut'),
                    ])
                    ->action(function (Order $record, array $data): void {
                        $oldStatus = $record->statut;
                        $record->statut = $data['statut'];
                        
                        // Add to history
                        $history = $record->historique ?? '';
                        $history .= date('Y-m-d H:i:s') . " - Statut changé de '{$oldStatus}' à '{$data['statut']}' par " . Auth::user()->name . "\n";
                        $record->historique = $history;
                        
                        $record->save();
                        
                        Notification::make()
                            ->title('Statut mis à jour')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (Tables\Actions\DeleteBulkAction $action) {
                            if (!Auth::user()->hasPermission('manage_orders')) {
                                Notification::make()
                                    ->title('Permission refusée')
                                    ->body('Vous n\'avez pas la permission de supprimer des commandes.')
                                    ->danger()
                                    ->send();
                                    
                                $action->cancel();
                            }
                        }),
                    BulkAction::make('export')
                        ->label('Exporter')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function ($records) {
                            return Excel::download(new OrdersExport($records), 'commandes.xlsx');
                        })
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation()
                        ->color('success'),
                    BulkAction::make('update_status')
                        ->label('Mettre à jour le statut')
                        ->icon('heroicon-o-arrow-path')
                        ->form([
                            Forms\Components\Select::make('statut')
                                ->options([
                                    'en attente' => 'En attente',
                                    'confirmé' => 'Confirmé',
                                    'expédié' => 'Expédié',
                                    'livré' => 'Livré',
                                    'annulé' => 'Annulé',
                                ])
                                ->required()
                                ->label('Nouveau statut'),
                        ])
                        ->action(function ($records, array $data) {
                            $count = 0;
                            foreach ($records as $record) {
                                $oldStatus = $record->statut;
                                $record->statut = $data['statut'];
                                
                                // Add to history
                                $history = $record->historique ?? '';
                                $history .= date('Y-m-d H:i:s') . " - Statut changé de '{$oldStatus}' à '{$data['statut']}' par " . Auth::user()->name . "\n";
                                $record->historique = $history;
                                
                                $record->save();
                                $count++;
                            }
                            
                            Notification::make()
                                ->title("{$count} commandes mises à jour")
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\OrderItemsRelationManager::class,
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
    
    public static function canAccess(): bool
    {
        return auth()->user()->hasPermission('manage_orders');
    }
}
