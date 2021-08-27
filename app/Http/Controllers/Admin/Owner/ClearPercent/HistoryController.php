<?php

namespace App\Http\Controllers\Admin\Owner\ClearPercent;

use App\Http\Controllers\Controller;
use App\Models\BetDetail;
use App\Models\ClearPercent;
use App\Models\ClearPercentDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
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
        return view('auth.agent_and_admin.owner.clear_percent.history');
    }

    public function get_clear_percents(Request $request)
    {
        $bet_detail_win = 0;
        $bet_detail_lose = 0;
        $agent = 0;
        $owner = 0;
        $request->validate(
            [
                "date_start" => "required",
                "date_end" => "required",
            ],
            [
                "date_start.required" => "กรุณาเลือกวันที่เริ่ม",
                "date_end.required" => "กรุณาเลือกวันที่สิ้นสุด"
            ]
        );

        $clear_percent = DB::table('clear_percents')
            ->join('clear_percent_details', 'clear_percents.id', '=', 'clear_percent_details.clear_id')
            ->join('bet_details', 'bet_details.id', '=', 'clear_percent_details.bet_detail_id')
            ->join('users', 'users.id', '=', 'bet_details.user_id')
            ->join('admins', 'admins.id', '=', 'users.admin_id')
            ->select(
                // 'clear_percents.*',
                'clear_percents.created_at',
                'clear_percents.id',
                'admins.name',
                'admins.username',
                'admins.share_percentage',
                'admins.telephone',
                // 'clear_percent.id'
            )
            ->where('clear_percents.admin_id', Auth::guard('admin')->user()->id)
            ->where('clear_percents.created_at', '>=', $request->date_start)
            ->where('clear_percents.created_at', '<=', $request->date_end)
            ->groupBy('clear_percents.id')
            ->orderBy('clear_percents.created_at', 'ASC')
            ->get();
        for ($i = 0; $i < count($clear_percent); $i++) {
            $clear_percent[$i]->created_at2 = Carbon::parse($clear_percent[$i]->created_at)->locale('th')->diffForHumans();

            $clear_percent_details = ClearPercentDetail::where('clear_id', $clear_percent[$i]->id)->get();

            for ($y = 0; $y < count($clear_percent_details); $y++) {
                $bet_detail = Betdetail::find($clear_percent_details[$y]->bet_detail_id);
                // return dd($bet_detail);
                if ($bet_detail->status == 1) {
                    $bet_detail_win += $bet_detail->money;
                } else if ($bet_detail->status == 2) {
                    $bet_detail_lose += $bet_detail->money;
                }
            }
            $total_wl =  $bet_detail_lose - $bet_detail_win;
            $agent =  $total_wl * ($clear_percent[$i]->share_percentage / 100);
            $owner = $total_wl - $agent;
            $clear_percent[$i]->agent = $agent;
            $clear_percent[$i]->owner = $owner;
            $bet_detail_win = 0;
            $bet_detail_lose = 0;
            $agent = 0;
            $owner = 0;
        }
        // return dd($clear_percent);
        return response()->json(["data" => $clear_percent]);
    }

    public function history($id)
    {
        $clear_percent = ClearPercent::find($id);
        $users = DB::table('clear_percents')
            ->join('clear_percent_details', 'clear_percents.id', '=', 'clear_percent_details.clear_id')
            ->join('bet_details', 'bet_details.id', '=', 'clear_percent_details.bet_detail_id')
            ->join('users', 'users.id', '=', 'bet_details.user_id')
            ->join('admins', 'admins.id', '=', 'users.admin_id')
            ->select(
                // 'clear_percents.*',
                // 'clear_percents.created_at',
                'clear_percents.id',
                'users.id',
                'users.name',
                'users.username',
                'users.telephone',
            )
            ->where('clear_percents.id', $id)
            ->groupBy('users.id')
            ->get();
        // return dd($clear_percent2);
        return view('auth.agent_and_admin.owner.clear_percent.history_detail', compact('clear_percent', 'users'));
    }
}
