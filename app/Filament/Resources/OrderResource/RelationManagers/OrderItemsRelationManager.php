<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    
    protected static ?string $title = 'Détails de la commande';

    public function form(Form $form): Form
    {
        return $form
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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('product.nom')
                    ->label('Produit'),
                
                Tables\Columns\TextColumn::make('quantite')
                    ->label('Quantité')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('prix_unitaire')
                    ->money('EUR')
                    ->label('Prix unitaire')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('total')
                    ->money('EUR')
                    ->label('Total')
                    ->getStateUsing(fn ($record) => $record->prix_unitaire * $record->quantite),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
