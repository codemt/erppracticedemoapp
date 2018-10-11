<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class RedirectIfAdminAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next, $guard = 'admin') {

        if (Auth::guard($guard)->check()) {
            return redirect('/admin/dashboard');
        }
    // public function handle($request, Closure $next)
    // {
    return $next($request);
    // }
}
}
