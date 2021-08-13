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
        // $agents =  DB::table('users')
        // ->join('admins', 'users.admin_id', '=', 'admins.id')
        // ->join('bet_details', 'users.id', '=', 'bet_details.user_id')
        // ->select(
        //     'admins.id As id',
        //     'admins.name',
        //     'admins.username As username',
        //     'admins.telephone As telephone',
        //     'admins.share_percentage',
        //     'bet_details.money',
        //     'bet_details.status',
        //     )
        // ->selectRaw('sum(bet_details.money) as sum')
        // ->where('admins.admin_id', Auth::guard('admin')->user()->id)
        // ->where('bet_details.status', 2)
        // ->groupBy('id')
        // ->paginate(10);

        $admin_id = Auth::guard('admin')->user()->id;
        $agents = DB::select(DB::raw(
            "
            SELECT q1.id,
            q1.name,
            q1.username,
            q1.telephone,
            q1.share_percentage,
            q1.agent_lose,
            q2.agent_win
            FROM
            (
            SELECT admins.*, sum(bet_details.money) AS agent_lose
            FROM admins
            inner join users on admins.id = users.admin_id
            inner join bet_details on users.id = bet_details.user_id
            WHERE bet_details.status = 2
            GROUP BY admins.id
            ) q1,
            (
                SELECT sum(bet_details.money) AS agent_win
                FROM admins
                inner join users on admins.id = users.admin_id
                inner join bet_details on users.id = bet_details.user_id
                WHERE bet_details.status = 1
                GROUP BY admins.id
            ) q2
            where q1.admin_id = $admin_id
            GROUP by id
                "
        ));
        return dd($agents);
        return view('auth.agent_and_admin.owner.clear_percent.home', compact('agents'));
    }
}
