<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\Client;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index()
    {
        // Date ranges
        $today = Carbon::now();
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $sixtyDaysAgo = Carbon::now()->subDays(60);
        
        // Get total sales for the last 30 days
        $totalSales = Order::where('created_at', '>=', $thirtyDaysAgo)
            ->sum('total');
            
        // Get total sales for the period 30-60 days ago (for growth calculation)
        $previousPeriodSales = Order::where('created_at', '>=', $sixtyDaysAgo)
            ->where('created_at', '<', $thirtyDaysAgo)
            ->sum('total');
            
        // Calculate sales growth
        $salesGrowth = $previousPeriodSales > 0 
            ? round((($totalSales - $previousPeriodSales) / $previousPeriodSales) * 100, 1)
            : 100;
            
        // Total orders in the last 30 days
        $totalOrders = Order::where('created_at', '>=', $thirtyDaysAgo)->count();
        
        // Total orders in the previous period
        $previousPeriodOrders = Order::where('created_at', '>=', $sixtyDaysAgo)
            ->where('created_at', '<', $thirtyDaysAgo)
            ->count();
            
        // Calculate orders growth
        $ordersGrowth = $previousPeriodOrders > 0 
            ? round((($totalOrders - $previousPeriodOrders) / $previousPeriodOrders) * 100, 1)
            : 100;
            
        // Average order value
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;
        
        // Previous period average order value
        $previousAvgOrderValue = $previousPeriodOrders > 0 
            ? $previousPeriodSales / $previousPeriodOrders
            : 0;
            
        // Calculate average order growth
        $avgOrderGrowth = $previousAvgOrderValue > 0 
            ? round((($averageOrderValue - $previousAvgOrderValue) / $previousAvgOrderValue) * 100, 1)
            : 0;
            
        // Unique customers in the last 30 days
        $uniqueCustomers = Order::where('created_at', '>=', $thirtyDaysAgo)
            ->distinct('client_id')
            ->count('client_id');
            
        // Unique customers in the previous period
        $previousPeriodCustomers = Order::where('created_at', '>=', $sixtyDaysAgo)
            ->where('created_at', '<', $thirtyDaysAgo)
            ->distinct('client_id')
            ->count('client_id');
            
        // Calculate customers growth
        $customersGrowth = $previousPeriodCustomers > 0 
            ? round((($uniqueCustomers - $previousPeriodCustomers) / $previousPeriodCustomers) * 100, 1)
            : 100;
            
        // Get order status counts
        $pendingOrders = Order::where('statut', 'en attente')->count();
        $shippedOrders = Order::where('statut', 'expédié')->count();
        $deliveredOrders = Order::where('statut', 'livré')->count();
        $cancelledOrders = Order::where('statut', 'annulé')->count();
        
        // Best selling products
        $bestSellingProducts = DB::table('produits')
            ->join('details_commandes', 'produits.id', '=', 'details_commandes.produit_id')
            ->join('commandes', 'details_commandes.commande_id', '=', 'commandes.id')
            ->select(
                'produits.id',
                'produits.nom',
                'produits.prix',
                DB::raw('SUM(details_commandes.quantite) as total_sales'),
                DB::raw('SUM(details_commandes.quantite * details_commandes.prix_unitaire) as revenue')
            )
            ->where('commandes.created_at', '>=', $thirtyDaysAgo)
            ->groupBy('produits.id', 'produits.nom', 'produits.prix')
            ->orderBy('total_sales', 'desc')
            ->limit(5)
            ->get();
            
        // Product statistics
        $productStats = DB::table('produits')
            ->leftJoin('details_commandes', 'produits.id', '=', 'details_commandes.produit_id')
            ->leftJoin('commandes', 'details_commandes.commande_id', '=', 'commandes.id')
            ->select(
                'produits.id',
                'produits.nom',
                'produits.prix',
                'produits.image', // Changed from image_url to image
                'produits.vues',
                DB::raw('COALESCE(SUM(details_commandes.quantite), 0) as total_sales'),
                DB::raw('COALESCE(SUM(details_commandes.quantite * details_commandes.prix_unitaire), 0) as revenue'),
                DB::raw('CASE WHEN produits.vues > 0 THEN COALESCE(SUM(details_commandes.quantite), 0) / produits.vues ELSE 0 END as conversion_rate')
            )
            ->groupBy('produits.id', 'produits.nom', 'produits.prix', 'produits.image', 'produits.vues') // Added image here
            ->orderBy('total_sales', 'desc')
            ->limit(20)
            ->get();
            
        // Get all products for the filter
        $allProducts = Product::select('id', 'nom')->orderBy('nom')->get();
        
        // Recent orders
        $recentOrders = Order::with(['client', 'orderDetails'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        // Sales chart data (daily for 30 days)
        $salesData = Order::where('created_at', '>=', $thirtyDaysAgo)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as daily_total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        $salesChartLabels = [];
        $salesChartData = [];
        
        // Create a range of 30 days
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $salesChartLabels[] = Carbon::parse($date)->format('d M');
            
            // Find data for this date or use 0
            $daySales = $salesData->firstWhere('date', $date);
            $salesChartData[] = $daySales ? $daySales->daily_total : 0;
        }
        
        return view('admin.statistics.index', compact(
            'totalSales',
            'salesGrowth',
            'totalOrders',
            'ordersGrowth',
            'averageOrderValue',
            'avgOrderGrowth',
            'uniqueCustomers',
            'customersGrowth',
            'pendingOrders',
            'shippedOrders',
            'deliveredOrders',
            'cancelledOrders',
            'bestSellingProducts',
            'productStats',
            'allProducts',
            'recentOrders',
            'salesChartLabels',
            'salesChartData'
        ));
    }
}
