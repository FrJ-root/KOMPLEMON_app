<?php

namespace App\Exports;
use Illuminate\Support\Collection;
use App\Models\Order;

class OrdersExport
{
    protected $order = null;

    public function __construct($order = null)
    {
        $this->order = $order;
    }

    public function collection()
    {
        if ($this->order) {
            return collect([$this->order]);
        }
        
        return Order::with(['client', 'items.product'])->get();
    }

    public function toArray()
    {
        $orders = $this->collection();
        $data = [];
        $data[] = [
            'ID',
            'Date',
            'Total',
            'Email',
            'Client',
            'Statut',
            'Adresse',
            'Produits',
            'Téléphone',
        ];
        
        foreach ($orders as $order) {
            if (!$order->relationLoaded('items')) {
                $order->load('items.product');
            }
            
            if (!$order->relationLoaded('client')) {
                $order->load('client');
            }
            
            $products = $order->items->map(function ($item) {
                return $item->quantite . 'x ' . ($item->product->nom ?? 'Produit inconnu') . ' (' . number_format($item->prix_unitaire, 2) . '€)';
            })->implode(', ');
            
            $data[] = [
                $products,
                $order->id,
                $order->statut,
                $order->client->nom ?? 'N/A',
                $order->client->email ?? 'N/A',
                $order->client->adresse ?? 'N/A',
                $order->client->telephone ?? 'N/A',
                number_format($order->total, 2) . '€',
                $order->date_commande->format('d/m/Y H:i'),
            ];
        }
        
        return $data;
    }
}