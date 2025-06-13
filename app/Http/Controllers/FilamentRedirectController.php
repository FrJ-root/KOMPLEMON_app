<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FilamentRedirectController extends Controller
{
    /**
     * Redirect to Filament admin panel.
     */
    public function redirectToFilament()
    {
        return redirect('/admin/dashboard');
    }
    
    /**
     * Redirect to Filament orders resource.
     */
    public function redirectToOrders()
    {
        return redirect('/admin/resources/orders');
    }
    
    /**
     * Redirect to Filament customers resource.
     */
    public function redirectToCustomers()
    {
        return redirect('/admin/resources/customers');
    }
    
    /**
     * Redirect to Filament order exports.
     */
    public function redirectToExport()
    {
        // Change to the appropriate export route in your app
        return response()->streamDownload(function () {
            $orders = \App\Models\Order::with('client')->get();
            echo "ID,Client,Date,Total,Statut\n";
            foreach ($orders as $order) {
                echo "{$order->id},{$order->client->nom},{$order->date_commande},{$order->total},{$order->statut}\n";
            }
        }, 'orders_export.csv');
    }
}
