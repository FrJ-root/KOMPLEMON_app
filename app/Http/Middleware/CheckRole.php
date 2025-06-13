<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        $user = auth()->user();
        
        // Allow administrators to access everything
        if ($user->role === 'administrateur') {
            return $next($request);
        }
        
        // Check if user has one of the allowed roles
        if (in_array($user->role, $roles)) {
            return $next($request);
        }
        
        // If accessing dashboard route directly, redirect to appropriate section
        if ($request->is('admin/dashboard')) {
            return match ($user->role) {
                'gestionnaire_produits' => redirect('/admin/products'),
                'gestionnaire_commandes' => redirect('/admin/orders'),
                'editeur_contenu' => redirect('/admin/articles'),
                default => redirect('/admin/dashboard'),
            };
        }
        
        // Default unauthorized access
        abort(403, 'Unauthorized action.');
    }
}