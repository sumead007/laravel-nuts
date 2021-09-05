<?php

namespace App\Http\Controllers\User\HistoryBet;

use App\Http\Controllers\Controller;
use App\Models\BetDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryBetController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    public function index()
    {
        return view('auth.user.history_bet.home');
    }

    public function get_bet_details(Request $request)
    {
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
        $histories = BetDetail::where('user_id', Auth::guard('user')->user()->id)
            ->where('created_at', '>=', $request->date_start)
            ->where('created_at', '<=', $request->date_end)
            ->orderBy('created_at', 'ASC')->get();
        for ($i = 0; $i < count($histories); $i++) {
            $histories[$i]->created_at2 = Carbon::parse($histories[$i]->created_at)->locale('th')->isoFormat('LLL');
        }
        return response()->json(["data" => $histories]);
    }
}
