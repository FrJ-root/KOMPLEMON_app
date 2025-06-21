<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\OrderResource;
use App\Models\Client;
use App\Models\Order;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions;

class ViewCustomerOrders extends Page
{
    use InteractsWithTable;
    
    protected static string $resource = CustomerResource::class;

    protected static string $view = 'filament.resources.customer-resource.pages.view-customer-orders';
    
    protected static ?string $title = 'Commandes du client';
    
    public Client $customer;
    
    public function mount(Client $record): void
    {
        $this->customer = $record;
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->query(Order::where('client_id', $this->customer->id))
            ->columns([
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
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label('Modifier')
                    ->url(fn (Order $record) => OrderResource::getUrl('edit', ['record' => $record]))
                    ->icon('heroicon-o-pencil-square'),
            ])
            ->paginated([10, 25, 50, 100]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Retour')
                ->url($this->getResource()::getUrl('index'))
                ->icon('heroicon-o-arrow-left'),
            
            Actions\Action::make('new_order')
                ->label('Nouvelle commande')
                ->url(OrderResource::getUrl('create'))
                ->icon('heroicon-o-plus'),
        ];
    }
}
    }
}
