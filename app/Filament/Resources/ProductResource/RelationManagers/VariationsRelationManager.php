<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VariationsRelationManager extends RelationManager
{
    protected static string $relationship = 'variations';

    protected static ?string $recordTitleAttribute = 'size';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('size')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->required(),
                
                Forms\Components\TextInput::make('flavor')
                    ->maxLength(255),
                
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->required()
                    ->prefix('€'),
                
                Forms\Components\TextInput::make('stock_quantity')
                    ->numeric()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('size'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('flavor'),
                Tables\Columns\TextColumn::make('price')
                    ->money('EUR'),
                Tables\Columns\TextColumn::make('stock_quantity'),
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
