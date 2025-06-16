<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FilamentRedirectController extends Controller
{
    public function redirectToFilament()
    {
        return redirect('/admin/filament');
    }
    
    public function redirectToOrders()
    {
        return redirect('/admin/filament/orders');
    }
    
    public function redirectToCustomers()
    {
        return redirect('/admin/filament/customers');
    }
}
