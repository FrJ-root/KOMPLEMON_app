<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Order;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:administrateur,gestionnaire_commandes');
    }
    
    public function index(Request $request)
    {
        $query = Client::query();
        
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
    
    public function create()
    {
        return view('admin.clients.create');
    }
    
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
    
    public function show(Client $client)
    {
        $client->load('orders');
        $orderCount = $client->orders->count();
        $totalSpent = $client->orders->sum('total');
        
        $recentOrders = $client->orders()->latest('date_commande')->take(5)->get();
        
        return view('admin.clients.show', compact('client', 'orderCount', 'totalSpent', 'recentOrders'));
    }
    
    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }
    
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
    
    public function destroy(Client $client)
    {
        $orderCount = Order::where('client_id', $client->id)->count();
        
        if ($orderCount > 0) {
            return back()->with('error', "Ce client ne peut pas être supprimé car il a {$orderCount} commandes associées.");
        }
        
        $client->delete();
        
        return redirect()->route('clients.index')
            ->with('success', 'Client supprimé avec succès.');
    }
}
