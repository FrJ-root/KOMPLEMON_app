<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\Client;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Illuminate\Support\Facades\DB;
use Filament\Support\Enums\IconPosition;
use App\Filament\Resources\OrderResource;
use App\Filament\Resources\CustomerResource;

class OrderManagerDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static string $view = 'filament.pages.order-manager-dashboard';
    
    protected static ?string $title = 'Tableau de bord';
    
    protected static ?int $navigationSort = 1;
    
    protected static ?string $slug = 'dashboard';
    
    public function getOrderStats(): array
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::where('statut', 'en attente')->count();
        $todayOrders = Order::whereDate('date_commande', today())->count();
        $todayPendingOrders = Order::whereDate('date_commande', today())->where('statut', 'en attente')->count();
        
        $currentMonthRevenue = Order::whereMonth('date_commande', now()->month)
            ->whereYear('date_commande', now()->year)
            ->sum('total');
            
        $lastMonthRevenue = Order::whereMonth('date_commande', now()->subMonth()->month)
            ->whereYear('date_commande', now()->subMonth()->year)
            ->sum('total');
            
        $percentChange = $lastMonthRevenue > 0 
            ? round((($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) 
            : 100;
        
        $customersCount = Client::count();
        $newCustomersLastMonth = Client::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $customerPercentChange = Client::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        $customerPercentChange = $customerPercentChange > 0
            ? round((($newCustomersLastMonth - $customerPercentChange) / $customerPercentChange) * 100, 1)
            : 100;
        
        return [
            'total_orders' => $totalOrders,
            'today_orders' => $todayOrders,
            'pending_orders' => $pendingOrders,
            'customers_count' => $customersCount,
            'today_pending' => $todayPendingOrders,
            'monthly_revenue' => $currentMonthRevenue,
            'revenue_percent_change' => $percentChange,
            'customer_percent_change' => $customerPercentChange,
        ];
    }
    
    public function getRecentOrders(): array
    {
        $recentOrders = Order::with('client')
            ->latest('date_commande')
            ->take(5)
            ->get();
            
        return [
            'orders' => $recentOrders,
        ];
    }
    
    protected function getHeaderActions(): array
    {
        return [
            Action::make('view_orders')
                ->label('Voir toutes les commandes')
                ->url(fn (): string => OrderResource::getUrl('index'))
                ->icon('heroicon-m-arrow-right')
                ->iconPosition(IconPosition::After),
                
            Action::make('view_customers')
                ->label('Gérer les clients')
                ->url(fn (): string => CustomerResource::getUrl('index'))
                ->icon('heroicon-m-users')
                ->color('gray'),
                
            Action::make('export_orders')
                ->label('Exporter les commandes')
                ->url(route('admin.orders.export'))
                ->icon('heroicon-m-arrow-down-tray')
                ->color('success'),
        ];
    }
    
    public static function canAccess(): bool
    {
        return auth()->user()->role === 'gestionnaire_commandes' || 
               auth()->user()->role === 'administrateur';
    }
}