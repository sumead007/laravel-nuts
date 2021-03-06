<?php

namespace App\Http\Controllers\User\Bet;

use App\Events\Histories;
use App\Events\ShowBetsNow;
use App\Http\Controllers\Controller;
use App\Models\Bet;
use App\Models\BetDetail;
use App\Models\ConfigTurnOnTurnOff;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BetController extends Controller
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

    public function bet(Request $request)
    {
        $request->validate(
            [
                "number" => "required|numeric",
                "money" => "required|regex:/^\d+(\.\d{1,2})?$/|numeric|min:0|not_in:0",
            ],
            [
                //bank_cus_id
                "number.required" => "กรุณาเลือกตัวเลข",
                "number.numeric" => "ต้องเป็นเฉพาะตัวเลข",
                //bank_id
                "money.required" => "กรุณากรอกช่องนี้",
                "money.regex" => "กรุณากรอกในรูปแบบจำนวนเงินห้ามติดลบ เช่น 200, 2000, 50000",
                "money.min" => "ห้ามเป็นเลข0",
                "money.not_in" => "ห้ามเป็นเลข0",

            ],
        );

        $user = User::find(Auth::guard('user')->user()->id);
        $money_rate = ($request->money * 3) + ($request->money * 0.03);
        if ($user->money < $money_rate) return response()->json(['error' => 'Error msg'], 403);
        // return dd($request->number);
        $time_trun_on_turn_of = ConfigTurnOnTurnOff::first()->updated_at;
        $data = Bet::updateOrCreate(['time_open' => $time_trun_on_turn_of], [
            "time_open" => $time_trun_on_turn_of,
            "time_off" => "",
            "admin_id" => "1",
        ]);
        $data2 = BetDetail::updateOrCreate(['id' => ""], [
            "status" => 0,
            "user_id" => Auth::guard('user')->user()->id,
            "number" => $request->number,
            "money" => $request->money,
            "bet_id" => $data->id,
            "money_deducted_first" => $money_rate,
        ]);
        event(new ShowBetsNow([
            "username" =>  $user->username,
            "number" => $data2->number,
            "money" => $data2->money,
            "status" => $data2->status,
        ]));
        $user->money -= $money_rate;
        $user->update();
        $data2->created_at2 = Carbon::parse($data2->created_at)->locale('th')->diffForHumans();
        return response()->json(["data" => $data2, "money" => $user->money]);
    }
}
