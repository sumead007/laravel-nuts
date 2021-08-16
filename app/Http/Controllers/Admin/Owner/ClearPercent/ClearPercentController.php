<?php

namespace App\Http\Controllers\Admin\Owner\ClearPercent;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClearPercentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('chk_position');
    }

    public function index()
    {

        // $agents_win = DB::table('admins')
        //     ->join('users', 'admins.id', '=', 'users.admin_id')
        //     ->join('bet_details', 'users.id', '=', 'bet_details.user_id')
        //     ->select(
        //         'admins.*',
        //         'users.username as user_username',
        //         'bet_details.status as status',
        //         'bet_details.user_id as user_id',
        //         DB::raw('SUM(bet_details.money) as money'),
        //     )
        //     ->where('admins.admin_id', Auth::guard('admin')->user()->id)
        //     ->where('status', 1)
        //     ->groupBy('admins.id')
        //     // ->orWhere('admins.admin_id', Auth::guard('admin')->user()->id)
        //     ->orderByDesc('created_at')
        //     ->get();

        // $agents_lose = DB::table('admins')
        //     ->leftJoin('users', 'admins.id', '=', 'users.admin_id')
        //     ->leftJoin('bet_details', 'users.id', '=', 'bet_details.user_id')
        //     ->select(
        //         'admins.*',
        //         'users.username as user_username',
        //         'bet_details.status as status',
        //         'bet_details.user_id as user_id',
        //         DB::raw('SUM(bet_details.money) as money'),
        //     )
        //     ->where('admins.admin_id', Auth::guard('admin')->user()->id)
        //     ->where('status', 2)
        //     ->groupBy('admins.id')
        //     // ->orWhere('admins.admin_id', Auth::guard('admin')->user()->id)
        //     ->orderByDesc('created_at')
        //     ->get();
        // return dd($agents);

        $agents = Admin::where('admins.admin_id', Auth::guard('admin')->user()->id)->paginate(10);
        return view('auth.agent_and_admin.owner.clear_percent.home', compact('agents'));
    }

    
}
