<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CustomerResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'Clients';

    protected static ?string $modelLabel = 'Client';

    protected static ?string $pluralModelLabel = 'Clients';

    protected static ?string $navigationGroup = 'Gestion des Commandes';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations du client')
                    ->schema([
                        Forms\Components\TextInput::make('nom')
                            ->required()
                            ->maxLength(255)
                            ->label('Nom complet'),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('telephone')
                            ->tel()
                            ->maxLength(50)
                            ->label('Téléphone'),

                        Forms\Components\Textarea::make('adresse')
                            ->rows(3)
                            ->maxLength(500)
                            ->label('Adresse complète'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom')
                    ->searchable()
                    ->sortable()
                    ->label('Nom'),
                    
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('telephone')
                    ->searchable()
                    ->label('Téléphone'),
                    
                Tables\Columns\TextColumn::make('orders_count')
                    ->counts('orders')
                    ->label('Commandes')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y')
                    ->label('Inscrit le')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('has_orders')
                    ->label('Avec commandes')
                    ->query(fn (Builder $query): Builder => $query->has('orders'))
                    ->toggle(),
                    
                Tables\Filters\Filter::make('recent')
                    ->label('Récents (30 jours)')
                    ->query(fn (Builder $query): Builder => $query->where('created_at', '>=', now()->subDays(30)))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('view_orders')
                    ->label('Voir commandes')
                    ->icon('heroicon-o-shopping-bag')
                    ->url(fn (Client $record) => 
                        CustomerResource::getUrl('view-orders', ['record' => $record])
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export')
                        ->label('Exporter')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function ($records) {
                            return response()->streamDownload(function () use ($records) {
                                echo "ID,Nom,Email,Téléphone,Adresse,Nombre de commandes\n";
                                foreach ($records as $record) {
                                    echo "{$record->id},{$record->nom},{$record->email},{$record->telephone},\"{$record->adresse}\",{$record->orders->count()}\n";
                                }
                            }, 'clients.csv');
                        }),
                ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\OrdersRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
            'view-orders' => Pages\ViewCustomerOrders::route('/{record}/orders'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->role === 'gestionnaire_commandes' || 
               auth()->user()->role === 'administrateur';
    }
}
