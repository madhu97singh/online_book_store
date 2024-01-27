<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use Config;
class AfterLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle($request, Closure $next)
    {
        if(Auth::check())
        {
            if(auth()->user()->role_id == Config('constant.roles.Admin'))
            {
                return $next($request);
            }
            else if(auth()->user()->role_id == Config('constant.roles.User'))
            {
                return $next($request);
            }
            else{
                Auth::logout();
                return redirect('/login');
            }
           
        }
        else
        {
            return redirect('/login');
        }
    }
}
