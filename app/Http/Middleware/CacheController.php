<?php

namespace App\Http\Middleware;

use Closure;

class CacheController
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // return $next($request);
        $response = $next($request);
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0, max-age=0');
        // $response->headers->set('Access-Control-Allow-Origin', '*');
        // $response->headers->set('Access-Control-Allow-Methods', 'GET, POST');      
        // $response->headers->set('Access-Control-Allow-Headers','x-csrf-token,x-requested-with');
        
        return $response;
    }
}
