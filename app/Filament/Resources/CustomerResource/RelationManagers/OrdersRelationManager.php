<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';
    
    protected static ?string $title = 'Commandes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Commande #'),
                    
                Tables\Columns\TextColumn::make('date_commande')
                    ->dateTime('d/m/Y H:i')
                    ->label('Date'),
                    
                Tables\Columns\BadgeColumn::make('statut')
                    ->colors([
                        'danger' => 'annulé',
                        'warning' => 'en attente',
                        'success' => 'livré',
                        'primary' => 'confirmé',
                        'info' => 'expédié',
                    ])
                    ->label('Statut'),
                    
                Tables\Columns\TextColumn::make('total')
                    ->money('EUR')
                    ->label('Total'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['date_commande'] = now();
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
