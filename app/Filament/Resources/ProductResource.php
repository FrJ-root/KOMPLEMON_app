<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
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

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationGroup = 'Products Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nom')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\RichEditor::make('description')
                    ->required()
                    ->columnSpanFull(),
                    
                Forms\Components\Select::make('categorie_id')
                    ->relationship('category', 'nom')
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nom')
                            ->required(),
                        Forms\Components\Textarea::make('description'),
                    ]),
                    
                Forms\Components\TextInput::make('prix')
                    ->required()
                    ->numeric()
                    ->prefix('€'),
                    
                Forms\Components\TextInput::make('prix_promo')
                    ->numeric()
                    ->prefix('€'),
                    
                Forms\Components\TextInput::make('stock')
                    ->required()
                    ->numeric(),
                    
                Forms\Components\Select::make('statut')
                    ->options([
                        'publié' => 'Publié',
                        'brouillon' => 'Brouillon',
                    ])
                    ->required(),
                    
                Forms\Components\Repeater::make('ingredients')
                    ->schema([
                        Forms\Components\TextInput::make('name')->required(),
                        Forms\Components\TextInput::make('quantity')->required(),
                    ])
                    ->columns(2),
                    
                Forms\Components\Repeater::make('nutritional_values')
                    ->schema([
                        Forms\Components\TextInput::make('nutrient')->required(),
                        Forms\Components\TextInput::make('value')->required(),
                        Forms\Components\TextInput::make('unit')->required(),
                    ])
                    ->columns(3),
                    
                SpatieMediaLibraryFileUpload::make('images')
                    ->collection('product_images')
                    ->multiple()
                    ->maxFiles(5)
                    ->columnSpanFull(),
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
                    
                Tables\Columns\TextColumn::make('stock')
                    ->sortable(),
                    
                Tables\Columns\SelectColumn::make('statut')
                    ->options([
                        'publié' => 'Publié',
                        'brouillon' => 'Brouillon',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'nom'),
                    
                Tables\Filters\SelectFilter::make('statut')
                    ->options([
                        'publié' => 'Publié',
                        'brouillon' => 'Brouillon',
                    ]),
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

    public static function getRelations(): array{
        return [
            ProductResource\RelationManagers\VariationsRelationManager::class,
        ];
    }

    public static function getPages(): array{
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}