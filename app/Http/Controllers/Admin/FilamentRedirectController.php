<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FilamentRedirectController extends Controller
{
    /**
     * Redirect to Filament admin panel.
     */
    public function redirectToFilament()
    {
        return redirect('/admin/filament');
    }
    
    /**
     * Redirect to Filament orders resource.
     */
    public function redirectToOrders()
    {
        return redirect('/admin/filament/orders');
    }
    
    /**
     * Redirect to Filament customers resource.
     */
    public function redirectToCustomers()
    {
        return redirect('/admin/filament/customers');
    }
}
