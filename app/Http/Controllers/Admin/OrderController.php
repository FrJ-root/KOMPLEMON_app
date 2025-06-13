<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:administrateur,gestionnaire_commandes');
    }
    
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        // Get a fresh query builder instance to avoid any issues
        $query = Order::query()->with('client');
        
        // Filter by status
        if ($request->has('statut') && $request->statut) {
            $query->where('statut', $request->statut);
        }
        
        // Filter by date range
        if ($request->has('date_debut') && $request->date_debut) {
            $query->whereDate('date_commande', '>=', $request->date_debut);
        }
        
        if ($request->has('date_fin') && $request->date_fin) {
            $query->whereDate('date_commande', '<=', $request->date_fin);
        }
        
        // Search by ID or client name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('nom', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Get the total count before pagination
        $totalCount = $query->count();
        
        // Apply pagination with a fixed number per page
        $orders = $query->latest('date_commande')->paginate(10);
        
        // Create view data
        $viewData = [
            'orders' => $orders,
            'totalCount' => $totalCount,
            'filter' => [
                'status' => $request->statut ?? '',
                'date_debut' => $request->date_debut ?? '',
                'date_fin' => $request->date_fin ?? '',
                'search' => $request->search ?? ''
            ]
        ];
        
        // Return view with orders
        return view('admin.orders.index', $viewData);
    }
    
    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        $clients = Client::orderBy('nom')->get();
        $products = Product::where('statut', 'publié')->orderBy('nom')->get();
        
        return view('admin.orders.create', compact('clients', 'products'));
    }
    
    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'items' => 'required|array|min:1',
            'items.*.produit_id' => 'required|exists:produits,id',
            'items.*.quantite' => 'required|integer|min:1',
            'items.*.prix_unitaire' => 'required|numeric|min:0',
            'statut' => 'required|in:en attente,confirmé,expédié,livré,annulé',
        ]);
        
        // Calculate total
        $total = 0;
        foreach ($validated['items'] as $item) {
            $total += $item['quantite'] * $item['prix_unitaire'];
        }
        
        try {
            DB::beginTransaction();
            
            // Create order
            $order = Order::create([
                'client_id' => $validated['client_id'],
                'date_commande' => now(),
                'statut' => $validated['statut'],
                'total' => $total,
                'historique' => now()->format('Y-m-d H:i:s') . " - Commande créée par " . auth()->user()->name . "\n",
            ]);
            
            // Create order items
            foreach ($validated['items'] as $item) {
                OrderDetail::create([
                    'commande_id' => $order->id,
                    'produit_id' => $item['produit_id'],
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $item['prix_unitaire'],
                ]);
                
                // Update product stock if tracking is enabled
                $product = Product::find($item['produit_id']);
                if ($product && $product->suivi_stock) {
                    $product->decrement('stock', $item['quantite']);
                }
            }
            
            DB::commit();
            
            return redirect()->route('orders.index')
                ->with('success', 'Commande créée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation error: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de la commande.');
        }
    }
    
    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['client', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }
    
    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        $order->load(['client', 'items.product']);
        $clients = Client::orderBy('nom')->get();
        $products = Product::where('statut', 'publié')->orderBy('nom')->get();
        
        return view('admin.orders.edit', compact('order', 'clients', 'products'));
    }
    
    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:details_commandes,id',
            'items.*.produit_id' => 'required|exists:produits,id',
            'items.*.quantite' => 'required|integer|min:1',
            'items.*.prix_unitaire' => 'required|numeric|min:0',
            'statut' => 'required|in:en attente,confirmé,expédié,livré,annulé',
        ]);
        
        // Calculate total
        $total = 0;
        foreach ($validated['items'] as $item) {
            $total += $item['quantite'] * $item['prix_unitaire'];
        }
        
        try {
            DB::beginTransaction();
            
            // Update order status history if status changed
            $historique = $order->historique ?? '';
            if ($order->statut !== $validated['statut']) {
                $historique .= now()->format('Y-m-d H:i:s') . " - Statut changé de '{$order->statut}' à '{$validated['statut']}' par " . auth()->user()->name . "\n";
            }
            
            // Update order
            $order->update([
                'client_id' => $validated['client_id'],
                'statut' => $validated['statut'],
                'total' => $total,
                'historique' => $historique,
            ]);
            
            // Get existing items
            $existingItems = $order->items->keyBy('id');
            $updatedItemIds = [];
            
            // Update or create items
            foreach ($validated['items'] as $itemData) {
                if (isset($itemData['id']) && $existingItems->has($itemData['id'])) {
                    // Update existing item
                    $item = $existingItems->get($itemData['id']);
                    
                    // Calculate stock change
                    $stockChange = $item->quantite - $itemData['quantite'];
                    
                    $item->update([
                        'produit_id' => $itemData['produit_id'],
                        'quantite' => $itemData['quantite'],
                        'prix_unitaire' => $itemData['prix_unitaire'],
                    ]);
                    
                    // Update product stock if tracking is enabled and quantity changed
                    if ($stockChange != 0) {
                        $product = Product::find($itemData['produit_id']);
                        if ($product && $product->suivi_stock) {
                            $product->increment('stock', $stockChange);
                        }
                    }
                    
                    $updatedItemIds[] = $item->id;
                } else {
                    // Create new item
                    $item = OrderDetail::create([
                        'commande_id' => $order->id,
                        'produit_id' => $itemData['produit_id'],
                        'quantite' => $itemData['quantite'],
                        'prix_unitaire' => $itemData['prix_unitaire'],
                    ]);
                    
                    // Update product stock if tracking is enabled
                    $product = Product::find($itemData['produit_id']);
                    if ($product && $product->suivi_stock) {
                        $product->decrement('stock', $itemData['quantite']);
                    }
                    
                    $updatedItemIds[] = $item->id;
                }
            }
            
            // Delete removed items
            foreach ($existingItems as $item) {
                if (!in_array($item->id, $updatedItemIds)) {
                    // Return stock to inventory if tracking is enabled
                    $product = Product::find($item->produit_id);
                    if ($product && $product->suivi_stock) {
                        $product->increment('stock', $item->quantite);
                    }
                    
                    $item->delete();
                }
            }
            
            DB::commit();
            
            return redirect()->route('orders.index')
                ->with('success', 'Commande mise à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order update error: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour de la commande.');
        }
    }
    
    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order)
    {
        try {
            DB::beginTransaction();
            
            // Return stock to inventory if tracking is enabled
            foreach ($order->items as $item) {
                $product = Product::find($item->produit_id);
                if ($product && $product->suivi_stock) {
                    $product->increment('stock', $item->quantite);
                    $product->save();
                }
            }
            
            // First delete order items
            $order->items()->delete();
            
            // Then delete the order itself
            $order->delete();
            
            DB::commit();
            
            return redirect()->route('orders.index')
                ->with('success', 'Commande supprimée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order deletion error: ' . $e->getMessage());
            
            return redirect()->route('orders.index')
                ->with('error', 'Une erreur est survenue lors de la suppression de la commande: ' . $e->getMessage());
        }
    }
    
    /**
     * Export orders to Excel or show export form.
     */
    public function export(Request $request)
    {
        // If this is just a GET request without parameters, show the export form
        if ($request->method() === 'GET' && !$request->has('format')) {
            return view('admin.orders.export');
        }
        
        try {
            $query = Order::with(['client', 'items.product']);
            
            // Filter by period
            if ($request->has('period')) {
                $period = $request->input('period');
                
                switch ($period) {
                    case 'today':
                        $query->whereDate('date_commande', today());
                        break;
                    case 'week':
                        $query->whereBetween('date_commande', [now()->startOfWeek(), now()->endOfWeek()]);
                        break;
                    case 'month':
                        $query->whereMonth('date_commande', now()->month)
                              ->whereYear('date_commande', now()->year);
                        break;
                    case 'custom':
                        if ($request->has('date_debut') && $request->date_debut) {
                            $query->whereDate('date_commande', '>=', $request->date_debut);
                        }
                        if ($request->has('date_fin') && $request->date_fin) {
                            $query->whereDate('date_commande', '<=', $request->date_fin);
                        }
                        break;
                }
            }
            
            // Filter by status
            if ($request->has('status') && is_array($request->status) && !in_array('all', $request->status)) {
                $query->whereIn('statut', $request->status);
            }
            
            // Get the orders
            $orders = $query->latest('date_commande')->get();
            
            // Handle different export formats
            $format = $request->input('format', 'excel');
            $fileName = 'commandes_' . now()->format('Y-m-d');
            
            switch ($format) {
                case 'csv':
                    return Excel::download(new OrdersExport($orders), $fileName . '.csv', \Maatwebsite\Excel\Excel::CSV);
                case 'pdf':
                    // For PDF, you'll need to set up a PDF exporter
                    // This is just a placeholder, adjust based on your PDF library
                    return Excel::download(new OrdersExport($orders), $fileName . '.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
                default: // excel
                    return Excel::download(new OrdersExport($orders), $fileName . '.xlsx');
            }
        } catch (\Exception $e) {
            // Log the error
            Log::error('Order export error: ' . $e->getMessage());
            
            // Redirect back with error message
            return redirect()->back()->with('error', 'Erreur lors de l\'exportation: ' . $e->getMessage());
        }
    }
    
    /**
     * Show export form with selected orders.
     */
    public function exportForm(Request $request)
    {
        if (!$request->has('selected_orders') || count($request->selected_orders) === 0) {
            return redirect()->route('orders.index')
                ->with('error', 'Veuillez sélectionner au moins une commande à exporter.');
        }
        
        $selectedOrderIds = $request->selected_orders;
        $selectedOrders = Order::with(['client', 'items.product'])
            ->whereIn('id', $selectedOrderIds)
            ->get();
        
        return view('admin.orders.export', compact('selectedOrders'));
    }
    
    /**
     * Export a specific order to Excel.
     */
    public function exportSingle(Order $order)
    {
        try {
            // Load relationships
            $order->load(['client', 'items.product']);
            
            // Create a collection with this single order
            $orders = collect([$order]);
            
            // Export the order
            return Excel::download(
                new OrdersExport($orders),
                'commande_' . $order->id . '_' . now()->format('Y-m-d') . '.xlsx'
            );
        } catch (\Exception $e) {
            Log::error('Order export error: ' . $e->getMessage());
            
            return back()->with('error', 'Une erreur est survenue lors de l\'exportation: ' . $e->getMessage());
        }
    }
}
