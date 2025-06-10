<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash')
                ->color('danger'),
            Actions\Action::make('duplicate')
                ->icon('heroicon-o-document-duplicate')
                ->color('gray')
                ->action(function () {
                    $product = $this->record;
                    $newProduct = $product->replicate();
                    $newProduct->nom = $product->nom . ' (copie)';
                    $newProduct->save();
                    
                    Notification::make()
                        ->title('Produit dupliqué avec succès')
                        ->success()
                        ->send();
                    
                    return redirect()->route('filament.admin.resources.products.edit', $newProduct->id);
                }),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
