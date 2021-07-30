<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = "/";
    protected $redirectTo = "{{route('login')}}";


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'username' => ['required', 'string', 'min:6', 'max:20', 'unique:users', 'unique:admins'],
            'telephone' => ['required', 'numeric',  'digits:10', 'unique:users', 'unique:admins'],
            'password' => ['required', 'string', 'min:8', 'max:20', 'confirmed'],
            'admin_id' => ['required', 'numeric'],
        ]);
    }

    ///คอมเม้นนี้คือ หลังจากสมัครจะไม่ให้ล็อกอิน
    // /**
    //  * Create a new user instance after a valid registration.
    //  *
    //  * @param  array  $data
    //  * @return \App\Models\User
    //  */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'telephone' => $data['telephone'],
            'money' => 0.0,
            'admin_id' => $data['admin_id'],
            'password' => Hash::make($data['password']),
        ]);
        // คอมเม้นนี้คือ หลังจากสมัครจะไม่ให้ล็อกอิน **อย่าลืมเอา return ออก
        event(new Registered($user));
        session()->flash('success', 'ลงทะเบียนสำเร็จกรุณาล็อกอิน');
        throw new AuthenticationException();
        // flash('Registration successful! Awaiting approval from admin.')->success()->important();
    }
}
