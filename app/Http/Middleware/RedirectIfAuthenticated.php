<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Closure;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                switch ($user->role) {
                    case 'administrateur':
                        return redirect('/admin/dashboard');
                    
                    case 'gestionnaire_produits':
                        return redirect('/admin/products');
                        
                    case 'gestionnaire_commandes':
                        return redirect('/admin/dashboard');
                        
                    case 'editeur_contenu':
                        return redirect('/admin/articles');
                        
                    default:
                        return redirect(RouteServiceProvider::HOME);
                }
            }
        }

        return $next($request);
    }
}
