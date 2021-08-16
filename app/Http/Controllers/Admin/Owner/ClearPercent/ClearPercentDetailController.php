<?php

namespace App\Http\Controllers\Admin\Owner\ClearPercent;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Bet;
use App\Models\BetDetail;
use App\Models\ClearPercent;
use App\Models\ClearPercentDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClearPercentDetailController extends Controller
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

    public function index($id)
    {
        $agent = Admin::find($id);
        $users = User::where('admin_id', $id)->get();
        // $agents_win = DB::table('users')
        //     ->join('bet_details', 'users.id', '=', 'bet_details.user_id')
        //     ->select(
        //         'users.*',
        //         'bet_details.status as status',
        //         'bet_details.user_id as user_id',
        //         DB::raw('SUM(bet_details.money) as money'),
        //     )
        //     ->where('users.admin_id', $id)
        //     ->where('status', 1)
        //     // ->orWhere('admins.admin_id', Auth::guard('admin')->user()->id)
        //     ->orderByDesc('created_at')
        //     ->get();
        // return dd($agents_win);
        return view('auth.agent_and_admin.owner.clear_percent.detail_clear', compact('agent', 'users'));
    }
    public function store(Request $request)
    {
        $data_pass = $request->data;
        $agent_id_pass =  $request->agent_id;
        $money_pass = abs($request->money);
        // return dd($money_pass, $agent_id_pass,  $data_pass);
        $clear_percent = new ClearPercent();
        $clear_percent->admin_id = Auth::guard('admin')->user()->id;
        $clear_percent->save();
        for ($i = 0; $i < count($data_pass); $i++) {
            BetDetail::find($data_pass[$i])->update([
                "status" => 3
            ]);
            ClearPercentDetail::create([
                "clear_id" => $clear_percent->id,
                "bet_detail_id" => $data_pass[$i]
            ]);
        }
        Admin::find($agent_id_pass)->update([
            "credit" => $money_pass
        ]);
        return response()->json(["msg" => "เคลียบินนี้สำเร็จ"], 200);
    }
}
