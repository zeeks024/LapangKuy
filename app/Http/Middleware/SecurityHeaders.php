<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        
        // Allow images from our domain and trusted sources
        $cspDirectives = "default-src 'self'; " .
                         "img-src 'self' data: https://secure.example.com https://*.cloudfront.net; " . 
                         "script-src 'self' 'unsafe-inline'; " .
                         "style-src 'self' 'unsafe-inline'; " .
                         "font-src 'self';";
        
        $response->headers->set('Content-Security-Policy', $cspDirectives);
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        return $response;
    }
}
