<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaResource\Pages;
use App\Models\ProductMedia;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class MediaResource extends Resource
{
    protected static ?string $model = ProductMedia::class;

    protected static ?string $navigationIcon = 'heroicon-o-photograph';
    
    protected static ?string $navigationGroup = 'Produits';
    
    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
        return auth()->user()->hasPermission('manage_media');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('url')
                ->label('URL')
                ->required()
                ->url()
                ->maxLength(255),
            
            Forms\Components\Select::make('type')
                ->label('Type')
                ->options([
                    'image' => 'Image',
                    'video' => 'Vidéo',
                ])
                ->default('image')
                ->required(),
            
            Forms\Components\Toggle::make('is_active')
                ->label('Actif')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Text::make('id')
                    ->label('ID')
                    ->sortable(),
                
                Tables\Columns\Text::make('url')
                    ->label('URL')
                    ->url()
                    ->sortable(),
                
                Tables\Columns\Text::make('type')
                    ->label('Type')
                    ->sortable(),
                
                Tables\Columns\Boolean::make('is_active')
                    ->label('Actif')
                    ->sortable(),
                
                Tables\Columns\Text::make('created_at')
                    ->label('Date de création')
                    ->dateTime()
                    ->sortable(),
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMedia::route('/'),
            'create' => Pages\CreateMedia::route('/create'),
            'edit' => Pages\EditMedia::route('/{record}/edit'),
        ];
    }    
}