<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Add the date
        $data['date_commande'] = now();
        
        // Add to history
        $data['historique'] = now()->format('Y-m-d H:i:s') . " - Commande créée par " . auth()->user()->name . "\n";
        
        return $data;
    }
}
