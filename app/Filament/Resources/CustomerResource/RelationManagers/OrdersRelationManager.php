<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';
    
    protected static ?string $title = 'Commandes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('statut')
                    ->options([
                        'en attente' => 'En attente',
                        'confirmé' => 'Confirmé',
                        'expédié' => 'Expédié',
                        'livré' => 'Livré',
                        'annulé' => 'Annulé',
                    ])
                    ->default('en attente')
                    ->required()
                    ->label('Statut'),
                    
                Forms\Components\DateTimePicker::make('date_commande')
                    ->required()
                    ->label('Date de commande'),
                    
                Forms\Components\TextInput::make('total')
                    ->numeric()
                    ->prefix('€')
                    ->required()
                    ->label('Total'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('N° Commande')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('date_commande')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->label('Date'),
                    
                Tables\Columns\TextColumn::make('total')
                    ->money('EUR')
                    ->sortable()
                    ->label('Total'),
                    
                Tables\Columns\BadgeColumn::make('statut')
                    ->colors([
                        'danger' => 'annulé',
                        'warning' => 'en attente',
                        'success' => 'livré',
                        'primary' => 'confirmé',
                        'info' => 'expédié',
                    ])
                    ->label('Statut'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('statut')
                    ->options([
                        'en attente' => 'En attente',
                        'confirmé' => 'Confirmé',
                        'expédié' => 'Expédié',
                        'livré' => 'Livré',
                        'annulé' => 'Annulé',
                    ])
                    ->label('Statut'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nouvelle commande')
                    ->url(route('filament.admin.resources.orders.create')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn ($record) => route('filament.admin.resources.orders.edit', $record)),
                    
                Tables\Actions\Action::make('change_status')
                    ->label('Changer statut')
                    ->icon('heroicon-o-arrow-path')
                    ->form([
                        Forms\Components\Select::make('statut')
                            ->options([
                                'en attente' => 'En attente',
                                'confirmé' => 'Confirmé',
                                'expédié' => 'Expédié',
                                'livré' => 'Livré',
                                'annulé' => 'Annulé',
                            ])
                            ->required()
                            ->label('Nouveau statut'),
                        Forms\Components\Textarea::make('commentaire')
                            ->label('Commentaire (optionnel)'),
                    ])
                    ->action(function ($record, array $data): void {
                        $previousStatus = $record->statut;
                        
                        // Add to history
                        $history = $record->historique ?? '';
                        $history .= now()->format('Y-m-d H:i:s') . " - Statut changé de '{$previousStatus}' à '{$data['statut']}'";
                        
                        if (!empty($data['commentaire'])) {
                            $history .= " - Commentaire: {$data['commentaire']}";
                        }
                        
                        $history .= " - par " . auth()->user()->name . "\n";
                        
                        $record->update([
                            'statut' => $data['statut'],
                            'historique' => $history,
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
