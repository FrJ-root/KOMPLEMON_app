<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Exports\OrdersExport;
use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Client;
use App\Models\Order;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:administrateur,gestionnaire_commandes');
    }
    
    public function index(Request $request)
    {
        $query = Order::query()->with('client');
        
        if ($request->has('statut') && $request->statut) {
            $query->where('statut', $request->statut);
        }
        
        if ($request->has('date_debut') && $request->date_debut) {
            $query->whereDate('date_commande', '>=', $request->date_debut);
        }
        
        if ($request->has('date_fin') && $request->date_fin) {
            $query->whereDate('date_commande', '<=', $request->date_fin);
        }
        
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
        
        $totalCount = $query->count();
        
        $orders = $query->latest('date_commande')->paginate(10);
        
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
        
        return view('admin.orders.index', $viewData);
    }
    
    public function create()
    {
        $clients = Client::orderBy('nom')->get();
        $products = Product::where('statut', 'publié')->orderBy('nom')->get();
        
        return view('admin.orders.create', compact('clients', 'products'));
    }
    
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
        
        $total = 0;
        foreach ($validated['items'] as $item) {
            $total += $item['quantite'] * $item['prix_unitaire'];
        }
        
        try {
            DB::beginTransaction();
            
            $order = Order::create([
                'client_id' => $validated['client_id'],
                'date_commande' => now(),
                'statut' => $validated['statut'],
                'total' => $total,
                'historique' => now()->format('Y-m-d H:i:s') . " - Commande créée par " . auth()->user()->name . "\n",
            ]);
            
            foreach ($validated['items'] as $item) {
                OrderDetail::create([
                    'commande_id' => $order->id,
                    'produit_id' => $item['produit_id'],
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $item['prix_unitaire'],
                ]);
                
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
    
    public function show(Order $order)
    {
        $order->load(['client', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }
    
    public function edit(Order $order)
    {
        $order->load(['client', 'items.product']);
        $clients = Client::orderBy('nom')->get();
        $products = Product::where('statut', 'publié')->orderBy('nom')->get();
        
        return view('admin.orders.edit', compact('order', 'clients', 'products'));
    }
    
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
        
        $total = 0;
        foreach ($validated['items'] as $item) {
            $total += $item['quantite'] * $item['prix_unitaire'];
        }
        
        try {
            DB::beginTransaction();
            
            $historique = $order->historique ?? '';
            if ($order->statut !== $validated['statut']) {
                $historique .= now()->format('Y-m-d H:i:s') . " - Statut changé de '{$order->statut}' à '{$validated['statut']}' par " . auth()->user()->name . "\n";
            }
            
            $order->update([
                'client_id' => $validated['client_id'],
                'statut' => $validated['statut'],
                'total' => $total,
                'historique' => $historique,
            ]);
            
            $existingItems = $order->items->keyBy('id');
            $updatedItemIds = [];
            
            foreach ($validated['items'] as $itemData) {
                if (isset($itemData['id']) && $existingItems->has($itemData['id'])) {
                    $item = $existingItems->get($itemData['id']);
                    
                    $stockChange = $item->quantite - $itemData['quantite'];
                    
                    $item->update([
                        'produit_id' => $itemData['produit_id'],
                        'quantite' => $itemData['quantite'],
                        'prix_unitaire' => $itemData['prix_unitaire'],
                    ]);
                    
                    if ($stockChange != 0) {
                        $product = Product::find($itemData['produit_id']);
                        if ($product && $product->suivi_stock) {
                            $product->increment('stock', $stockChange);
                        }
                    }
                    
                    $updatedItemIds[] = $item->id;
                } else {
                    $item = OrderDetail::create([
                        'commande_id' => $order->id,
                        'produit_id' => $itemData['produit_id'],
                        'quantite' => $itemData['quantite'],
                        'prix_unitaire' => $itemData['prix_unitaire'],
                    ]);
                    
                    $product = Product::find($itemData['produit_id']);
                    if ($product && $product->suivi_stock) {
                        $product->decrement('stock', $itemData['quantite']);
                    }
                    
                    $updatedItemIds[] = $item->id;
                }
            }
            
            foreach ($existingItems as $item) {
                if (!in_array($item->id, $updatedItemIds)) {
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
    
    public function destroy(Order $order)
    {
        try {
            DB::beginTransaction();
            
            foreach ($order->items as $item) {
                $product = Product::find($item->produit_id);
                if ($product && $product->suivi_stock) {
                    $product->increment('stock', $item->quantite);
                    $product->save();
                }
            }
            
            $order->items()->delete();
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
    
    public function exportSingle(Order $order)
    {
        $order->load(['client', 'items.product']);
        
        $filename = 'commande_' . $order->id . '_' . now()->format('Y-m-d') . '.csv';
        
        $exporter = new \App\Exports\OrdersExport($order);
        $data = $exporter->toArray();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    public function export()
    {
        $filename = 'commandes_' . now()->format('Y-m-d') . '.csv';
        
        $exporter = new \App\Exports\OrdersExport();
        $data = $exporter->toArray();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function formatOrderItems($items)
    {
        return $items->map(function ($item) {
            return $item->quantite . 'x ' . ($item->product->nom ?? 'Produit inconnu');
        })->implode(', ');
    }
}