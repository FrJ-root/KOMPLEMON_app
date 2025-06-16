<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Http\Livewire\Auth\Login as FilamentLogin;

class Login extends FilamentLogin
{
    public function render()
    {
        return view('login.login');
    }
}
