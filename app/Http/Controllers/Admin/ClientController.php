<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Order;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:administrateur,gestionnaire_commandes');
    }
    
    /**
     * Display a listing of clients.
     */
    public function index(Request $request)
    {
        $query = Client::query();
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%");
            });
        }
        
        $clients = $query->latest()->paginate(15);
        
        return view('admin.clients.index', compact('clients'));
    }
    
    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        return view('admin.clients.create');
    }
    
    /**
     * Store a newly created client in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients',
            'telephone' => 'nullable|string|max:50',
            'adresse' => 'nullable|string',
        ]);
        
        Client::create($validated);
        
        return redirect()->route('clients.index')
            ->with('success', 'Client créé avec succès.');
    }
    
    /**
     * Display the specified client.
     */
    public function show(Client $client)
    {
        $client->load('orders');
        $orderCount = $client->orders->count();
        $totalSpent = $client->orders->sum('total');
        
        // Get the last 5 orders
        $recentOrders = $client->orders()->latest('date_commande')->take(5)->get();
        
        return view('admin.clients.show', compact('client', 'orderCount', 'totalSpent', 'recentOrders'));
    }
    
    /**
     * Show the form for editing the specified client.
     */
    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }
    
    /**
     * Update the specified client in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients,email,' . $client->id,
            'telephone' => 'nullable|string|max:50',
            'adresse' => 'nullable|string',
        ]);
        
        $client->update($validated);
        
        return redirect()->route('clients.index')
            ->with('success', 'Client mis à jour avec succès.');
    }
    
    /**
     * Remove the specified client from storage.
     */
    public function destroy(Client $client)
    {
        // Check if client has orders
        $orderCount = Order::where('client_id', $client->id)->count();
        
        if ($orderCount > 0) {
            return back()->with('error', "Ce client ne peut pas être supprimé car il a {$orderCount} commandes associées.");
        }
        
        $client->delete();
        
        return redirect()->route('clients.index')
            ->with('success', 'Client supprimé avec succès.');
    }
}
