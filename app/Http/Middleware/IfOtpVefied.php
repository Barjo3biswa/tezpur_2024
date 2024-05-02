<?php

namespace App\Http\Middleware;

use Closure, Auth;

class IfOtpVefied
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = "student")
    {
        if(Auth::guard($guard)->user()->otp_verified){
            // if(Auth::guard($guard)->user()->email_verified_at){
                return redirect(route("student.home"));
            // }
        }
        // if(Auth::guard($guard)->user()->email_verified_at){
            // return redirect(route("student.home"));
        // }
        return $next($request);
    }
}
