<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FilamentRedirectController extends Controller
{
    public function redirectToFilament()
    {
        return redirect('/admin/dashboard');
    }
    
    public function redirectToOrders()
    {
        return redirect('/admin/resources/orders');
    }
    
    public function redirectToCustomers()
    {
        return redirect('/admin/resources/customers');
    }
    
    public function redirectToExport()
    {
        return response()->streamDownload(function () {
            $orders = \App\Models\Order::with('client')->get();
            echo "ID,Client,Date,Total,Statut\n";
            foreach ($orders as $order) {
                echo "{$order->id},{$order->client->nom},{$order->date_commande},{$order->total},{$order->statut}\n";
            }
        }, 'orders_export.csv');
    }
}