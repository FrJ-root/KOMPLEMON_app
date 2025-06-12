<?php

namespace App\Filament\Pages;

use App\Models\Order;
use Filament\Pages\Page;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Action;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class OrdersReport extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static string $view = 'filament.pages.orders-report';
    
    protected static ?string $navigationLabel = 'Rapports des commandes';
    
    protected static ?string $navigationGroup = 'Gestion des Commandes';
    
    protected static ?int $navigationSort = 3;
    
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'start_date' => now()->subDays(30)->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Période du rapport')
                    ->description('Sélectionnez la période pour générer le rapport')
                    ->schema([
                        DatePicker::make('start_date')
                            ->label('Date de début')
                            ->required(),
                        DatePicker::make('end_date')
                            ->label('Date de fin')
                            ->required(),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }
    
    protected function getFormActions(): array
    {
        return [
            Action::make('generate')
                ->label('Générer le rapport')
                ->submit('generate'),
            Action::make('export')
                ->label('Exporter en Excel')
                ->action(function () {
                    $start_date = $this->data['start_date'];
                    $end_date = $this->data['end_date'];
                    
                    $orders = Order::whereBetween('date_commande', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])->get();
                    
                    return Excel::download(
                        new OrdersExport($orders),
                        'rapport_commandes_' . $start_date . '_' . $end_date . '.xlsx'
                    );
                })
                ->visible(fn() => auth()->user()->hasPermission('export_orders')),
        ];
    }
    
    public function generate(): void
    {
        $this->form->getState();
    }
    
    public function getOrdersData(): array
    {
        if (empty($this->data)) {
            return [
                'orders' => [],
                'total_sales' => 0,
                'average_order' => 0,
                'order_count' => 0,
                'status_counts' => [],
            ];
        }
        
        $start_date = $this->data['start_date'];
        $end_date = $this->data['end_date'];
        
        $orders = Order::whereBetween('date_commande', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])
            ->with('client')
            ->get();
            
        $totalSales = $orders->sum('total');
        $orderCount = $orders->count();
        $averageOrder = $orderCount > 0 ? $totalSales / $orderCount : 0;
        
        $statusCounts = $orders->groupBy('statut')
            ->map(function ($group) {
                return $group->count();
            })
            ->toArray();
            
        return [
            'orders' => $orders,
            'total_sales' => $totalSales,
            'average_order' => $averageOrder,
            'order_count' => $orderCount,
            'status_counts' => $statusCounts,
        ];
    }
    
    public static function canAccess(): bool
    {
        return auth()->user()->hasPermission('export_orders');
    }
}
