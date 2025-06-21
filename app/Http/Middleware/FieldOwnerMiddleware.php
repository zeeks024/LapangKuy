<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FieldOwnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow access for field_owner or admin
        if (Auth::check() && (Auth::user()->role === 'field_owner' || Auth::user()->role === 'admin')) {
            return $next($request);
        }
        
        // Redirect to home page with error message
        return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman ini. Halaman ini hanya untuk pemilik lapangan.');
    }
}
