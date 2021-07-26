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
            return response()->view('auth.user.home');
        } elseif (Auth::guard('admin')->check()) {
            return response()->view('auth.agent_and_admin.accept_topup.home');
        } else {
            return $next($request);
        }
    }
}
