<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Gerer les coupons';
    protected static ?string $slug = 'coupons';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->unique(ignorable: fn ($record) => $record)
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535),
                Forms\Components\TextInput::make('discount_amount')
                    ->numeric()
                    ->prefix('MAD'),
                Forms\Components\TextInput::make('discount_percent')
                    ->numeric()
                    ->suffix('%')
                    ->maxValue(100),
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
                Forms\Components\DateTimePicker::make('expires_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('discount_amount')
                    ->money('MAD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_percent')
                    ->suffix('%')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\Filter::make('active_and_not_expired')
                    ->label('Active & Not Expired')
                    ->query(fn ($query) => $query->where('is_active', true)
                        ->where(function($query) {
                            $query->whereNull('expires_at')
                                ->orWhere('expires_at', '>', now());
                        }))
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
