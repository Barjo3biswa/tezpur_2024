<?php

namespace App\Http\Middleware;

use Closure;

class StringNullToNull
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
        foreach ($request->input() as $key => $value) {
            if(!is_array($value)){
                if (strtolower($value) == "null") {
                    $request->request->set($key, null);
                }
            }
        }
        return $next($request);
    }
}
