<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Http\Livewire\Auth\Login as FilamentLogin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Login extends FilamentLogin
{
    public function render()
    {
        return view('login.login');
    }
}
