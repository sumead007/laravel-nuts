<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $credential = array("username" => $request->input('username'), 'password' => $request->input('password'),);
        if (Auth::guard('admin')->attempt($credential)) {
            // return dd(auth('customer')->user());
            if (Auth::guard('admin')->user()->status == 0) {
                return redirect('/admin/home');
            } else {
                Auth::logout();
                $request->session()->flush();
                return redirect()->route('login')->with('error', 'ชื่อผู้ใช้นี้ถูกระงับการใช้งานกรุณาติดต่อเจ้าของ');
            }
        } elseif (Auth::guard('user')->attempt($credential)) {
            if (Auth::guard('user')->user()->status == 0) {
                return redirect('/');
            } else {
                Auth::logout();
                $request->session()->flush();
                return redirect()->route('login')->with('error', 'ชื่อผู้ใช้นี้ถูกระงับการใช้งานกรุณาติดต่อเจ้าของ');
            }
        } else {
            return redirect()->route('login')->with('error', 'รหัสหรือชื่อผู้ใช้ไม่ถูกต้อง');
        }
    }
}
