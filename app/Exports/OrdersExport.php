<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Collection;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Client',
            'Email',
            'Téléphone',
            'Date',
            'Statut',
            'Total',
            'Produits',
        ];
    }

    public function map($order): array
    {
        $products = $order->items->map(function ($item) {
            return $item->quantite . 'x ' . $item->product->nom . ' (' . $item->prix_unitaire . '€)';
        })->implode(', ');

        return [
            $order->id,
            $order->client->nom ?? 'N/A',
            $order->client->email ?? 'N/A',
            $order->client->telephone ?? 'N/A',
            $order->date_commande->format('d/m/Y H:i'),
            $order->statut,
            $order->total . '€',
            $products,
        ];
    }
}
