<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationGroup = 'E-commerce';
    
    protected static ?int $navigationSort = 1;
    
    protected static ?string $recordTitleAttribute = 'nom';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('administrateur') || 
               auth()->user()->hasRole('gestionnaire_produits');
    }
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informations produit')
                            ->schema([
                                Forms\Components\TextInput::make('nom')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true),
                                
                                Forms\Components\Select::make('categorie_id')
                                    ->relationship('category', 'nom')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('nom')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\Textarea::make('description')
                                            ->maxLength(65535),
                                    ])
                                    ->required(),
                                
                                Forms\Components\RichEditor::make('description')
                                    ->columnSpanFull(),
                                
                                Forms\Components\Section::make('Images')
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('images')
                                            ->collection('product-images')
                                            ->multiple()
                                            ->maxFiles(5)
                                            ->image()
                                            ->imageResizeMode('cover')
                                            ->imageResizeTargetWidth('1200')
                                            ->imageResizeTargetHeight('800')
                                            ->enableDownload()
                                            ->enableOpen()
                                            ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                                    ])
                                    ->collapsible(),
                            ])
                            ->columns(2),
                        
                        Forms\Components\Section::make('Pricing')
                            ->schema([
                                Forms\Components\TextInput::make('prix')
                                    ->required()
                                    ->numeric()
                                    ->prefix('€')
                                    ->columnSpan(1),
                                
                                Forms\Components\TextInput::make('prix_promo')
                                    ->numeric()
                                    ->prefix('€')
                                    ->columnSpan(1),
                            ])
                            ->columns(2),
                        
                        Forms\Components\Section::make('Inventory')
                            ->schema([
                                Forms\Components\TextInput::make('stock')
                                    ->required()
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->columnSpan(1),
                                
                                Forms\Components\Toggle::make('suivi_stock')
                                    ->label('Activer le suivi de stock')
                                    ->default(true)
                                    ->columnSpan(2),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(['lg' => 2]),
                
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Status')
                            ->schema([
                                Forms\Components\Select::make('statut')
                                    ->options([
                                        'publié' => 'Publié',
                                        'brouillon' => 'Brouillon',
                                    ])
                                    ->default('brouillon')
                                    ->required(),
                                
                                Forms\Components\Toggle::make('featured')
                                    ->label('Produit mis en avant')
                                    ->default(false),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
                
                Forms\Components\Section::make('Composition et nutrition')
                    ->schema([
                        Forms\Components\Textarea::make('ingredients')
                            ->label('Ingrédients')
                            ->rows(4)
                            ->columnSpan(1),
                        
                        Forms\Components\Textarea::make('valeurs_nutritionnelles')
                            ->label('Valeurs nutritionnelles')
                            ->rows(4)
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsed()
                    ->columnSpanFull(),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->circular()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('nom')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('category.nom')
                    ->label('Catégorie')
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('prix')
                    ->label('Prix')
                    ->money('EUR')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('prix_promo')
                    ->label('Prix promo')
                    ->money('EUR')
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match(true) {
                        $state <= 0 => 'danger',
                        $state <= 5 => 'warning',
                        default => 'success',
                    }),
                
                Tables\Columns\SelectColumn::make('statut')
                    ->options([
                        'publié' => 'Publié',
                        'brouillon' => 'Brouillon',
                    ])
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'publié' => 'success',
                        'brouillon' => 'gray',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('statut')
                    ->options([
                        'publié' => 'Publié',
                        'brouillon' => 'Brouillon',
                    ]),
                
                Tables\Filters\SelectFilter::make('categorie')
                    ->relationship('category', 'nom'),
                
                Tables\Filters\Filter::make('stock_faible')
                    ->label('Stock faible')
                    ->query(fn (Builder $query): Builder => $query->where('stock', '<=', 5))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->iconButton(),
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil-square')  // Update the icon here
                    ->iconButton(),
                Tables\Actions\DeleteAction::make()
                    ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('categorize')
                        ->label('Changer la catégorie')
                        ->icon('heroicon-o-tag')
                        ->form([
                            Forms\Components\Select::make('categorie_id')
                                ->label('Catégorie')
                                ->relationship('category', 'nom')
                                ->required(),
                        ])
                        ->action(function ($records, array $data): void {
                            foreach ($records as $record) {
                                $record->update([
                                    'categorie_id' => $data['categorie_id'],
                                ]);
                            }
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
