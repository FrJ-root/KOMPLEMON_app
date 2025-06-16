<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Client;
use App\Models\Order;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index()
    {
        $today = Carbon::now();
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $sixtyDaysAgo = Carbon::now()->subDays(60);
        $totalSales = Order::where('created_at', '>=', $thirtyDaysAgo)
            ->sum('total');
        $previousPeriodSales = Order::where('created_at', '>=', $sixtyDaysAgo)
            ->where('created_at', '<', $thirtyDaysAgo)
            ->sum('total');
        $salesGrowth = $previousPeriodSales > 0 
            ? round((($totalSales - $previousPeriodSales) / $previousPeriodSales) * 100, 1)
            : 100;
        $totalOrders = Order::where('created_at', '>=', $thirtyDaysAgo)->count();
        $previousPeriodOrders = Order::where('created_at', '>=', $sixtyDaysAgo)
            ->where('created_at', '<', $thirtyDaysAgo)
            ->count();
        $ordersGrowth = $previousPeriodOrders > 0 
            ? round((($totalOrders - $previousPeriodOrders) / $previousPeriodOrders) * 100, 1)
            : 100;
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;
        $previousAvgOrderValue = $previousPeriodOrders > 0 
            ? $previousPeriodSales / $previousPeriodOrders
            : 0;
        $avgOrderGrowth = $previousAvgOrderValue > 0 
            ? round((($averageOrderValue - $previousAvgOrderValue) / $previousAvgOrderValue) * 100, 1)
            : 0;
        $uniqueCustomers = Order::where('created_at', '>=', $thirtyDaysAgo)
            ->distinct('client_id')
            ->count('client_id');
        $previousPeriodCustomers = Order::where('created_at', '>=', $sixtyDaysAgo)
            ->where('created_at', '<', $thirtyDaysAgo)
            ->distinct('client_id')
            ->count('client_id');
        $customersGrowth = $previousPeriodCustomers > 0 
            ? round((($uniqueCustomers - $previousPeriodCustomers) / $previousPeriodCustomers) * 100, 1)
            : 100;
            
        $pendingOrders = Order::where('statut', 'en attente')->count();
        $shippedOrders = Order::where('statut', 'expédié')->count();
        $deliveredOrders = Order::where('statut', 'livré')->count();
        $cancelledOrders = Order::where('statut', 'annulé')->count();
        
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
            
        $productStats = DB::table('produits')
            ->leftJoin('details_commandes', 'produits.id', '=', 'details_commandes.produit_id')
            ->leftJoin('commandes', 'details_commandes.commande_id', '=', 'commandes.id')
            ->select(
                'produits.id',
                'produits.nom',
                'produits.prix',
                'produits.image',
                'produits.vues',
                DB::raw('COALESCE(SUM(details_commandes.quantite), 0) as total_sales'),
                DB::raw('COALESCE(SUM(details_commandes.quantite * details_commandes.prix_unitaire), 0) as revenue'),
                DB::raw('CASE WHEN produits.vues > 0 THEN COALESCE(SUM(details_commandes.quantite), 0) / produits.vues ELSE 0 END as conversion_rate')
            )
            ->groupBy('produits.id', 'produits.nom', 'produits.prix', 'produits.image', 'produits.vues') // Added image here
            ->orderBy('total_sales', 'desc')
            ->limit(20)
            ->get();
            
        $allProducts = Product::select('id', 'nom')->orderBy('nom')->get();
        
        $recentOrders = Order::with(['client', 'orderDetails'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        $salesData = Order::where('created_at', '>=', $thirtyDaysAgo)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as daily_total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        $salesChartLabels = [];
        $salesChartData = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $salesChartLabels[] = Carbon::parse($date)->format('d M');
            
            $daySales = $salesData->firstWhere('date', $date);
            $salesChartData[] = $daySales ? $daySales->daily_total : 0;
        }
        
        return view('admin.statistics.index', compact(
            'bestSellingProducts',
            'averageOrderValue',
            'salesChartLabels',
            'uniqueCustomers',
            'customersGrowth',
            'cancelledOrders',
            'deliveredOrders',
            'avgOrderGrowth',
            'salesChartData',
            'pendingOrders',
            'shippedOrders',
            'productStats',
            'recentOrders',
            'ordersGrowth',
            'totalOrders',
            'allProducts',
            'salesGrowth',
            'totalSales',
        ));
    }
}