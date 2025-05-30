<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ProductsChart extends ChartWidget
{
    protected static ?string $heading = 'Ventes par mois';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = $this->getMonthlySalesData();
        
        return [
            'datasets' => [
                [
                    'label' => 'Ventes par mois (€)',
                    'data' => $data['values'],
                    'backgroundColor' => [
                        'rgba(75, 192, 192, 0.2)',
                    ],
                    'borderColor' => [
                        'rgb(75, 192, 192)',
                    ],
                    'borderWidth' => 1
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
    
    private function getMonthlySalesData(): array
    {
        $now = Carbon::now();
        $labels = [];
        $values = [];
        
        // Get data for last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            
            $labels[] = $month->format('M Y');
            
            $value = Order::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->where('statut', '!=', 'annulé')
                ->sum('total');
                
            $values[] = $value;
        }
        
        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }
}
