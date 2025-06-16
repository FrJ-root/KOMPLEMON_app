<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('login.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            
            return $this->sendLoginResponse($request);
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }
    
    protected function sendLoginResponse(Request $request)
    {
        session()->flash('welcome', 'Bienvenue, ' . Auth::user()->name . '!');
        
        $intended = redirect()->intended()->getTargetUrl();
        $defaultUrl = url('/admin/dashboard');
        
        if ($intended === $defaultUrl) {
            return $this->redirectBasedOnRole();
        }
        
        return redirect()->intended();
    }
    
    protected function redirectBasedOnRole()
    {
        $user = Auth::user();
        
        return match ($user->role) {
            'administrateur' => redirect('/admin/dashboard'),
            'gestionnaire_produits' => redirect('/admin/products'),
            'gestionnaire_commandes' => redirect('/admin/dashboard'),
            'editeur_contenu' => redirect('/admin/dashboard'),
            default => redirect('/admin/dashboard'),
        };
    }

    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}