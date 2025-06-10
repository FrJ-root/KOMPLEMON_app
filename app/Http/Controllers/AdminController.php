<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Set the welcome_admin session variable to trigger the welcome modal
        session(['welcome_admin' => true]);
        
        return view('admin.dashboard');
    }

    // ...existing methods...
}