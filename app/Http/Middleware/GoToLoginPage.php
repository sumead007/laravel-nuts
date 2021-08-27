<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoToLoginPage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('user')->check()) {
            return abort(403, 'กรุณาออกจากระบบก่อน');
        } elseif (Auth::guard('admin')->check()) {
            return abort(403, "กรุณาออกจากระบบก่อน");
        } else {
            return $next($request);
        }
    }
}
