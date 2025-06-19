<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionHasWebsocketAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->hasSession()) {
            throw new \RuntimeException('Session store not set on request.');
        }
        
        return $next($request);
    }
}