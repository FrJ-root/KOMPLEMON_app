<?php

namespace App\Filament\Resources;

use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CouponResource\Pages;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Form;
use App\Models\Coupon;
use Filament\Tables;
use Filament\Forms;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'E-commerce';

    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return __('Coupons');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
    
    public static function canAccess(): bool
    {
        return auth()->user()->role === 'administrateur';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('code')
                                    ->required()
                                    ->unique(Coupon::class, 'code', ignoreRecord: true)
                                    ->maxLength(20)
                                    ->placeholder('SUMMER2023')
                                    ->columnSpan(1),
                                
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Actif')
                                    ->default(true)
                                    ->columnSpan(1),
                            ]),
                        
                        Forms\Components\Tabs::make('Discount')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('Montant fixe')
                                    ->schema([
                                        Forms\Components\TextInput::make('discount_amount')
                                            ->label('Montant de la réduction (€)')
                                            ->numeric()
                                            ->minValue(0)
                                            ->rules(['required_without:discount_percent', 'nullable', 'numeric', 'min:0'])
                                            ->placeholder('5.99')
                                            ->suffix('€'),
                                    ]),
                                
                                Forms\Components\Tabs\Tab::make('Pourcentage')
                                    ->schema([
                                        Forms\Components\TextInput::make('discount_percent')
                                            ->label('Pourcentage de réduction')
                                            ->integer()
                                            ->minValue(1)
                                            ->maxValue(100)
                                            ->rules(['required_without:discount_amount', 'nullable', 'integer', 'min:1', 'max:100'])
                                            ->placeholder('10')
                                            ->suffix('%'),
                                    ]),
                            ])
                            ->columnSpanFull(),
                        
                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Date d\'expiration')
                            ->nullable()
                            ->placeholder('Sans date d\'expiration')
                            ->columnSpanFull(),
                        
                        Forms\Components\Textarea::make('description')
                            ->placeholder('Description du coupon')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->copyable()
                    ->copyMessage('Code copié!')
                    ->copyMessageDuration(1500),
                
                Tables\Columns\TextColumn::make('discount_type')
                    ->label('Type')
                    ->getStateUsing(function (Coupon $record): string {
                        if ($record->discount_amount) {
                            return 'Montant fixe';
                        }
                        
                        if ($record->discount_percent) {
                            return 'Pourcentage';
                        }
                        
                        return 'N/A';
                    }),
                
                Tables\Columns\TextColumn::make('discount_value')
                    ->label('Valeur')
                    ->getStateUsing(function (Coupon $record): string {
                        if ($record->discount_amount) {
                            return number_format($record->discount_amount, 2) . ' €';
                        }
                        
                        if ($record->discount_percent) {
                            return $record->discount_percent . '%';
                        }
                        
                        return 'N/A';
                    }),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expire le')
                    ->date('d/m/Y')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_active')
                    ->label('Statut')
                    ->options([
                        '1' => 'Actif',
                        '0' => 'Inactif',
                    ]),
                
                Tables\Filters\Filter::make('expires_at')
                    ->form([
                        Forms\Components\DatePicker::make('expires_from')
                            ->label('Expire après le'),
                        Forms\Components\DatePicker::make('expires_until')
                            ->label('Expire avant le'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['expires_from'],
                                fn (Builder $query, $date): Builder => $query->where('expires_at', '>=', $date),
                            )
                            ->when(
                                $data['expires_until'],
                                fn (Builder $query, $date): Builder => $query->where('expires_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('toggle')
                    ->label(fn (Coupon $record): string => $record->is_active ? 'Désactiver' : 'Activer')
                    ->icon(fn (Coupon $record): string => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn (Coupon $record): string => $record->is_active ? 'danger' : 'success')
                    ->action(function (Coupon $record): void {
                        $record->update(['is_active' => !$record->is_active]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activer')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn (Builder $query) => $query->update(['is_active' => true])),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Désactiver')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn (Builder $query) => $query->update(['is_active' => false])),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }    
}
