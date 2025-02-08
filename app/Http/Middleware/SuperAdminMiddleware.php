<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            return redirect()->route('home')->with('error', 'Acceso no autorizado');
        }

        return $next($request);
    }
}