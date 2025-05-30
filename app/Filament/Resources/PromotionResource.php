<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromotionResource\Pages;
use App\Models\Promotion;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class PromotionResource extends Resource
{
    protected static ?string $model = Promotion::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('code')
                                    ->label('Code promotionnel')
                                    ->required()
                                    ->maxLength(50)
                                    ->unique(ignoreRecord: true),
                                    
                                Select::make('type')
                                    ->label('Type de promotion')
                                    ->options([
                                        'pourcentage' => 'Pourcentage',
                                        'montant' => 'Montant fixe',
                                    ])
                                    ->required(),
                                    
                                TextInput::make('valeur')
                                    ->label('Valeur')
                                    ->numeric()
                                    ->required(),
                                    
                                TextInput::make('montant_minimum')
                                    ->label('Montant minimum d\'achat')
                                    ->numeric()
                                    ->nullable(),
                                    
                                DateTimePicker::make('date_debut')
                                    ->label('Date de début')
                                    ->required(),
                                    
                                DateTimePicker::make('date_fin')
                                    ->label('Date de fin')
                                    ->required(),
                                    
                                Toggle::make('utilisation_unique')
                                    ->label('Utilisation unique'),
                                    
                                TextInput::make('limite_utilisations')
                                    ->label('Limite d\'utilisations')
                                    ->numeric()
                                    ->nullable(),
                                    
                                Toggle::make('actif')
                                    ->label('Actif')
                                    ->default(true),
                                    
                                Forms\Components\Textarea::make('description')
                                    ->label('Description')
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge(),
                TextColumn::make('valeur')
                    ->label('Valeur')
                    ->formatStateUsing(fn (string $state, Promotion $record): string => 
                        $record->type === 'pourcentage' ? "{$state}%" : "{$state}€"
                    ),
                TextColumn::make('date_debut')
                    ->label('Début')
                    ->dateTime('d/m/Y')
                    ->sortable(),
                TextColumn::make('date_fin')
                    ->label('Fin')
                    ->dateTime('d/m/Y')
                    ->sortable(),
                TextColumn::make('nombre_utilisations')
                    ->label('Utilisations')
                    ->sortable(),
                ToggleColumn::make('actif')
                    ->label('Actif'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListPromotions::route('/'),
            'create' => Pages\CreatePromotion::route('/create'),
            'edit' => Pages\EditPromotion::route('/{record}/edit'),
        ];
    }
}
