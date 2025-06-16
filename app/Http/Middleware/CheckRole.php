<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        $user = auth()->user();
        
        if ($user->role === 'administrateur') {
            return $next($request);
        }
        
        if (in_array($user->role, $roles)) {
            return $next($request);
        }
        
        if ($request->is('admin/dashboard')) {
            return match ($user->role) {
                'gestionnaire_produits' => redirect('/admin/products'),
                'gestionnaire_commandes' => redirect('/admin/orders'),
                'editeur_contenu' => redirect('/admin/articles'),
                default => redirect('/admin/dashboard'),
            };
        }
        
        abort(403, 'Unauthorized action.');
    }
}