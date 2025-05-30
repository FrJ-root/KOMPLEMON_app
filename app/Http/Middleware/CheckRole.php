<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }
        
        // Convert comma-separated roles to array
        $allowedRoles = [];
        foreach ($roles as $role) {
            $allowedRoles = array_merge($allowedRoles, explode(',', $role));
        }
        
        if (in_array(Auth::user()->role, $allowedRoles)) {
            return $next($request);
        }
        
        // For AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'error' => 'Unauthorized. You do not have permission to access this section.',
            ], 403);
        }
        
        // For regular requests
        return redirect()->route('admin.dashboard')
            ->with('error', 'Vous n\'avez pas les permissions nécessaires pour accéder à cette section.');
    }
}
