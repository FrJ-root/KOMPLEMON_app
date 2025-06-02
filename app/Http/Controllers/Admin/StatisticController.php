<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
        
        // Total sales count
        $totalSales = Order::where('statut', 'terminé')->count();
        
        // Get monthly sales data for the past 6 months
        $monthlySales = $this->getMonthlySalesData();
        
        // Get best selling products
        $bestSellingProducts = $this->getTopSellingProducts(5);
        
        // Get product statistics (sales and views)
        $productStatistics = $this->getProductStatistics();
        
        // Get recent orders
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Get pending orders
        $pendingOrders = Order::with('user')
            ->where('statut', 'en attente')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Get order stats by status
        $ordersByStatus = $this->getOrdersByStatus();
        
        // Get daily revenue for the last 30 days
        $dailyRevenue = $this->getDailyRevenue();
        
        return view('admin.statistics.index', compact(
            'totalOrders',
            'totalUsers',
            'totalProducts',
            'totalRevenue',
            'totalSales',
            'monthlySales',
            'bestSellingProducts',
            'productStatistics',
            'recentOrders',
            'pendingOrders',
            'ordersByStatus',
            'dailyRevenue'
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
        // Updated to use correct table names
        return DB::table('details_commandes')
            ->join('commandes', 'details_commandes.commande_id', '=', 'commandes.id')
            ->join('produits', 'details_commandes.produit_id', '=', 'produits.id')
            ->join('categories', 'produits.categorie_id', '=', 'categories.id')
            ->where('commandes.statut', 'terminé')
            ->select(
                'categories.nom as category_name',
                DB::raw('SUM(details_commandes.quantite * details_commandes.prix_unitaire) as total_sales'),
                DB::raw('COUNT(DISTINCT commandes.id) as order_count')
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
        // Updated to use correct table names
        return DB::table('details_commandes')
            ->join('produits', 'details_commandes.produit_id', '=', 'produits.id')
            ->join('commandes', 'details_commandes.commande_id', '=', 'commandes.id')
            ->where('commandes.statut', 'terminé')
            ->select(
                'produits.id',
                'produits.nom',
                DB::raw('SUM(details_commandes.quantite) as quantity_sold'),
                DB::raw('SUM(details_commandes.quantite * details_commandes.prix_unitaire) as total_sales')
            )
            ->groupBy('produits.id', 'produits.nom')
            ->orderBy('quantity_sold', 'desc')
            ->limit($limit)
            ->get();
    }
    
    private function getProductStatistics()
    {
        return DB::table('produits')
            ->leftJoin('details_commandes', 'produits.id', '=', 'details_commandes.produit_id')
            ->leftJoin('commandes', function($join) {
                $join->on('details_commandes.commande_id', '=', 'commandes.id')
                    ->where('commandes.statut', '=', 'terminé'); // Add quotes around 'terminé'
            })
            ->select(
                'produits.id',
                'produits.nom as name',
                'produits.prix as price',
                'produits.image',
                DB::raw('COUNT(DISTINCT commandes.id) as order_count'),
                DB::raw('SUM(details_commandes.quantite) as quantity_sold'),
                DB::raw('produits.vues as views'),
                DB::raw('IFNULL(SUM(details_commandes.quantite * details_commandes.prix_unitaire), 0) as revenue')
            )
            ->groupBy('produits.id', 'produits.nom', 'produits.prix', 'produits.image', 'produits.vues')
            ->orderBy('quantity_sold', 'desc')
            ->limit(20)
            ->get();
    }
    
    private function getOrdersByStatus()
    {
        // Updated to use correct table name
        return DB::table('commandes')
            ->select('statut as status', DB::raw('COUNT(*) as count'))
            ->groupBy('statut')
            ->get();
    }
    
    private function getDailyRevenue()
    {
        return Order::where('statut', 'terminé')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
}
