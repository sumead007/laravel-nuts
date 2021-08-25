<?php

namespace App\Http\Controllers\User\ChangePassword;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class ChangePasswordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:user');
        Validator::extend('without_spaces', function ($attr, $value) {
            return preg_match('/^\S*$/u', $value);
        });
    }

    public function index(Request $request)
    {
        return view('auth.user.change_password.home');
    }

    public function store(Request $request)
    {

        $user = User::find(Auth::guard('user')->user()->id);
        // dd($admin);

        if (!Hash::check($request->password_old,  $user->password)) {
            return response()->json([
                "message" => "The give data was invalid.",
                'errors' => [
                    "password_old" => ["ไม่ตรงกับรหัสผ่านเก่า"]
                ],

            ], 422);
        } else {
            $request->validate(
                [
                    "username" => $request->username != $user->username ? "required|string|without_spaces|min:6|max:20|unique:users|unique:admins|regex:/(^([a-zA-Z]+)(\d+)?$)/u" : "required|string|without_spaces|min:6|max:20|unique:admins|regex:/(^([a-zA-Z]+)(\d+)?$)/u",
                    "password" => "required|min:8|max:20|confirmed",
                ],
                [
                    //username
                    "username.required" => "กรุณากรอกช่องนี้",
                    "username.min" => "กรุณากรอกระหว่าง 6-20 หลัก",
                    "username.max" => "กรุณากรอกระหว่าง 6-20 หลัก",
                    "username.unique" => "ชื่อผู้ใช้นี้มีคนใช้แล้ว",
                    "username.without_spaces" => "ระหว่างตัวอักษรห้ามมีช่องว่าง",
                    "username.regex" => "กรุณากรอกให้อยู่ในรูปแบบ (a-z)(0-9)",
                    //password
                    "password.required" => "กรุณากรอกช่องนี้",
                    "password.min" => "กรุณากรอกระหว่าง 8-20 หลัก",
                    "password.max" => "กรุณากรอกระหว่าง 8-20 หลัก",
                    "password.confirmed" => "กรุณายืนยันรหัสผ่านให้ถูกต้อง",
                ],
            );
            $user = User::updateOrCreate(['id' => Auth::guard('user')->user()->id], [
                "username" => $request->username,
                "password" => bcrypt($request->password),
            ]);
            return response()->json($user, 200);
        }
    }
}
