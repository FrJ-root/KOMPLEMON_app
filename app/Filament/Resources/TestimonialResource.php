<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Témoignages';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('nom_client')
                            ->label('Nom du client')
                            ->required()
                            ->maxLength(255),
                            
                        Textarea::make('contenu')
                            ->label('Témoignage')
                            ->required()
                            ->rows(4),
                            
                        FileUpload::make('media_url')
                            ->label('Photo du client')
                            ->directory('testimonials')
                            ->image()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeTargetWidth('300')
                            ->imageResizeTargetHeight('300'),
                            
                        Select::make('statut')
                            ->label('Statut')
                            ->options([
                                'approuvé' => 'Approuvé',
                                'en attente' => 'En attente',
                            ])
                            ->default('en attente')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('media_url')
                    ->label('Photo')
                    ->circular(),
                TextColumn::make('nom_client')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('contenu')
                    ->label('Témoignage')
                    ->limit(50),
                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approuvé' => 'success',
                        'en attente' => 'warning',
                    }),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('statut')
                    ->options([
                        'approuvé' => 'Approuvé',
                        'en attente' => 'En attente',
                    ])
                    ->label('Statut'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve')
                        ->label('Approuver')
                        ->icon('heroicon-o-check')
                        ->action(fn (Collection $records) => $records->each->update(['statut' => 'approuvé'])),
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
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
