<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'nom')
                    ->required()
                    ->searchable(),
                
                Forms\Components\Select::make('product_variation_id')
                    ->relationship('productVariation', 'size')
                    ->searchable(),
                
                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->required(),
                
                Forms\Components\TextInput::make('unit_price')
                    ->numeric()
                    ->required()
                    ->prefix('€'),
                
                Forms\Components\TextInput::make('total_price')
                    ->numeric()
                    ->required()
                    ->prefix('€'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.nom'),
                Tables\Columns\TextColumn::make('productVariation.size'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('unit_price')
                    ->money('EUR'),
                Tables\Columns\TextColumn::make('total_price')
                    ->money('EUR'),
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
