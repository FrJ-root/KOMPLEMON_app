<?php

namespace App\Filament\Resources;

use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Form;
use App\Models\Product;
use Filament\Tables;
use Filament\Forms;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationGroup = 'Produits';
    
    protected static ?string $navigationLabel = 'Produits';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('description')
                    ->columnSpan(2),
                Forms\Components\Select::make('categorie_id')
                    ->relationship('category', 'nom')
                    ->required(),
                Forms\Components\TextInput::make('prix')
                    ->required()
                    ->numeric()
                    ->prefix('€'),
                Forms\Components\TextInput::make('prix_promo')
                    ->numeric()
                    ->prefix('€'),
                Forms\Components\TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Textarea::make('ingredients')
                    ->columnSpan(2),
                Forms\Components\Textarea::make('valeurs_nutritionnelles')
                    ->columnSpan(2),
                Forms\Components\Select::make('statut')
                    ->options([
                        'publié' => 'Publié',
                        'brouillon' => 'Brouillon',
                    ])
                    ->default('brouillon')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.nom')
                    ->sortable(),
                Tables\Columns\TextColumn::make('prix')
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('prix_promo')
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->sortable(),
                Tables\Columns\SelectColumn::make('statut')
                    ->options([
                        'publié' => 'Publié',
                        'brouillon' => 'Brouillon',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('statut')
                    ->options([
                        'publié' => 'Publié',
                        'brouillon' => 'Brouillon',
                    ]),
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'nom'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
