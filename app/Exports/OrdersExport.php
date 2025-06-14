<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Collection;

class OrdersExport
{
    protected $order = null;

    /**
     * Constructor - can accept a single order or will export all orders
     */
    public function __construct($order = null)
    {
        $this->order = $order;
    }

    /**
     * Get the data for the export
     */
    public function collection()
    {
        if ($this->order) {
            return collect([$this->order]);
        }
        
        return Order::with(['client', 'items.product'])->get();
    }

    /**
     * Get the export data as an array
     */
    public function toArray()
    {
        $orders = $this->collection();
        $data = [];
        
        // Add headers
        $data[] = [
            'ID',
            'Date',
            'Client',
            'Email',
            'Téléphone',
            'Adresse',
            'Statut',
            'Total',
            'Produits',
        ];
        
        // Add order data
        foreach ($orders as $order) {
            // Load relationships if not already loaded
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
                $order->id,
                $order->date_commande->format('d/m/Y H:i'),
                $order->client->nom ?? 'N/A',
                $order->client->email ?? 'N/A',
                $order->client->telephone ?? 'N/A',
                $order->client->adresse ?? 'N/A',
                $order->statut,
                number_format($order->total, 2) . '€',
                $products,
            ];
        }
        
        return $data;
    }
}