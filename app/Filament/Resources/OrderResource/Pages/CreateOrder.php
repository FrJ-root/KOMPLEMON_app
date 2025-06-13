<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['historique'] = now()->format('Y-m-d H:i:s') . " - Commande créée par " . Auth::user()->name . "\n";
        
        return $data;
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
