<?php

namespace App\Http\Controllers\Admin\ManageUser;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManageUserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        if (Auth::guard('admin')->user()->position == 0) {
            $users = DB::table('users')
                ->join('admins', 'users.admin_id', '=', 'admins.id')
                ->select('users.*', 'admins.username as admin_username')
                ->where('users.admin_id', Auth::guard('admin')->user()->id)
                ->orWhere('admins.admin_id', Auth::guard('admin')->user()->id)
                ->get();
        } else {
            $users = User::where('admin_id', Auth::guard('admin')->user()->id)->orderByDesc('created_at')->paginate(10);
        }
        return view('auth.agent_and_admin.manage_user.manage_user', compact('users'));
    }

    public function get_agent()
    {
        $users = Admin::where('admin_id', Auth::guard('admin')->user()->id)->orderByDesc('created_at')->get();
        return response()->json(["data" => $users]);
    }
}
