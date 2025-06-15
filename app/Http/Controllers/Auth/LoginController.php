<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('login.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
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
    
    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
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
    
    /**
     * Redirect the user based on their role.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectBasedOnRole()
    {
        $user = Auth::user();
        
        return match ($user->role) {
            'administrateur' => redirect('/admin/dashboard'),
            'gestionnaire_produits' => redirect('/admin/products'),
            // Change to point to the Filament dashboard
            'gestionnaire_commandes' => redirect('/admin/dashboard'),
            'editeur_contenu' => redirect('/admin/dashboard'), // Redirect to dashboard instead of articles
            default => redirect('/admin/dashboard'),
        };
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}