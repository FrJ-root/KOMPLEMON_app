<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('changeStatus')
                ->label('Changer statut')
                ->icon('heroicon-o-tag')
                ->form([
                    \Filament\Forms\Components\Select::make('new_status')
                        ->label('Nouveau statut')
                        ->options([
                            'en attente' => 'En attente',
                            'confirmé' => 'Confirmé',
                            'expédié' => 'Expédié',
                            'livré' => 'Livré',
                            'annulé' => 'Annulé',
                        ])
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $this->record->updateStatus($data['new_status'], auth()->user()->name);
                    $this->refreshFormData(['record']);
                })
                ->color('warning')
                ->modalHeading('Changer le statut de la commande')
                ->modalDescription('Cette action sera enregistrée dans l\'historique de la commande.')
                ->modalSubmitActionLabel('Confirmer'),
            Actions\Action::make('print')
                ->label('Imprimer')
                ->icon('heroicon-o-printer')
                ->url(fn () => route('orders.print', ['order' => $this->record->id]))
                ->openUrlInNewTab(),
        ];
    }
}
