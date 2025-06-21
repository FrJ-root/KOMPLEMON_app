<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Actions;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('view')
                ->label('Voir')
                ->url(fn () => static::getResource()::getUrl('view', ['record' => $this->record]))
                ->icon('heroicon-o-eye'),
        ];
    }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $originalOrder = $this->record;
        
        if ($originalOrder->statut !== $data['statut']) {
            $historyEntry = now()->format('Y-m-d H:i:s') . " - Statut changé de '{$originalOrder->statut}' à '{$data['statut']}' par " . Auth::user()->name . "\n";
            $data['historique'] = ($originalOrder->historique ?? '') . $historyEntry;
        }
        
        return $data;
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}