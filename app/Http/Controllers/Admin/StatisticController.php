<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:administrateur');
    }
    
    public function index()
    {
        // Get summary statistics
        $totalOrders = Order::count();
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalRevenue = Order::where('statut', 'terminé')->sum('total');
        
        // Get monthly sales data for the past 6 months
        $monthlySales = $this->getMonthlySalesData();
        
        return view('admin.statistics.index', compact(
            'totalOrders',
            'totalUsers',
            'totalProducts',
            'totalRevenue',
            'monthlySales'
        ));
    }
    
    public function sales()
    {
        // Get sales data by various dimensions
        $salesByDay = $this->getSalesByDay();
        $salesByMonth = $this->getSalesByMonth();
        $salesByCategory = $this->getSalesByCategory();
        
        return view('admin.statistics.sales', compact(
            'salesByDay',
            'salesByMonth',
            'salesByCategory'
        ));
    }
    
    public function users()
    {
        // Get user registration data
        $registrationsByMonth = $this->getUserRegistrationsByMonth();
        $usersByRole = $this->getUsersByRole();
        
        return view('admin.statistics.users', compact(
            'registrationsByMonth',
            'usersByRole'
        ));
    }
    
    public function products()
    {
        // Get product statistics
        $topSellingProducts = $this->getTopSellingProducts();
        $productsByCategory = $this->getProductsByCategory();
        
        return view('admin.statistics.products', compact(
            'topSellingProducts',
            'productsByCategory'
        ));
    }
    
    private function getMonthlySalesData()
    {
        return Order::where('statut', 'terminé')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total) as total_sales')
            )
            ->groupBy('month', 'year')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
    }
    
    private function getSalesByDay()
    {
        return Order::where('statut', 'terminé')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total_sales'),
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
    }
    
    private function getSalesByMonth()
    {
        return Order::where('statut', 'terminé')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total) as total_sales'),
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy('month', 'year')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
    }
    
    private function getSalesByCategory()
    {
        // This is a simplified example; in a real app, you'd join with order items and products
        return DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('produits', 'order_items.produit_id', '=', 'produits.id')
            ->join('categories', 'produits.categorie_id', '=', 'categories.id')
            ->where('orders.statut', 'terminé')
            ->select(
                'categories.nom as category_name',
                DB::raw('SUM(order_items.quantite * order_items.prix) as total_sales'),
                DB::raw('COUNT(DISTINCT orders.id) as order_count')
            )
            ->groupBy('categories.id', 'categories.nom')
            ->orderBy('total_sales', 'desc')
            ->get();
    }
    
    private function getUserRegistrationsByMonth()
    {
        return User::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as user_count')
        )
        ->groupBy('month', 'year')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get();
    }
    
    private function getUsersByRole()
    {
        return User::select(
            'role',
            DB::raw('COUNT(*) as user_count')
        )
        ->groupBy('role')
        ->get();
    }
    
    private function getTopSellingProducts($limit = 10)
    {
        // This is a simplified example; in a real app, you'd join with order items
        return DB::table('order_items')
            ->join('produits', 'order_items.produit_id', '=', 'produits.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.statut', 'terminé')
            ->select(
                'produits.id',
                'produits.nom',
                DB::raw('SUM(order_items.quantite) as quantity_sold'),
                DB::raw('SUM(order_items.quantite * order_items.prix) as total_sales')
            )
            ->groupBy('produits.id', 'produits.nom')
            ->orderBy('quantity_sold', 'desc')
            ->limit($limit)
            ->get();
    }
    
    private function getProductsByCategory()
    {
        return DB::table('produits')
            ->join('categories', 'produits.categorie_id', '=', 'categories.id')
            ->select(
                'categories.nom as category_name',
                DB::raw('COUNT(*) as product_count')
            )
            ->groupBy('categories.id', 'categories.nom')
            ->orderBy('product_count', 'desc')
            ->get();
    }
}
