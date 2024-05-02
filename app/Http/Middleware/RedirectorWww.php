<?php

namespace App\Http\Middleware;

use Closure;
use Redirect;

class RedirectorWww
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
        $host = $request->header('host');
            if (substr($host, 0, 4) != 'www.') {
                if(!$request->secure()){
                    $request->server->set('HTTPS', true);
                }
                $request->headers->set('host', 'www.'.$host);
                return Redirect::to($request->path(),301);
            }else{
                if(!$request->secure()){
                    $request->server->set('HTTPS', true);
                    return Redirect::to($request->path(),301);
                }
            }
        return $next($request);
    }
}
