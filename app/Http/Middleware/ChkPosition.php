<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChkPosition
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
        if (Auth::guard('admin')->user()->position == 0) {
            return $next($request);
        } else {
            return abort(403, "คุณไม่มีสิทธิ์เข้าถึงหน้าดังกล่าว");
        }
    }
}
