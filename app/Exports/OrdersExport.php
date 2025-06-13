<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Database\Eloquent\Collection;

class OrdersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $orders;

    public function __construct($orders = null)
    {
        // If no orders are provided, get all orders
        $this->orders = $orders ?? Order::with(['client', 'items.product'])->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->orders;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Date',
            'Client',
            'Email',
            'Téléphone',
            'Adresse',
            'Statut',
            'Total',
            'Articles',
        ];
    }

    /**
     * @param mixed $order
     * @return array
     */
    public function map($order): array
    {
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

        return [
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

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the header row
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '6366F1']
                ],
            ],
            
            // Set borders for all cells
            'A1:I' . ($this->orders->count() + 1) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ],
        ];
    }
}
    }
}
