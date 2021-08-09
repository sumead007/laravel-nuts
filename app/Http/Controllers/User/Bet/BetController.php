<?php

namespace App\Http\Controllers\User\Bet;

use App\Events\ShowBetsNow;
use App\Http\Controllers\Controller;
use App\Models\Bet;
use App\Models\BetDetail;
use App\Models\ConfigTurnOnTurnOff;
use App\Models\User;
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
                "money" => "required|regex:/^\d+(\.\d{1,2})?$/",
            ],
            [
                // //bank_cus_id
                // "bank_cus_id.required" => "กรุณากรอกช่องนี้",
                // //bank_id
                // "bank_id.required" => "กรุณาเลือกธนาคารที่จะโอน",
                // //money
                // "money.required" => "กรุณากรอกช่องนี้",
                // "money.min" => "ต้องมีอย่างน้อย2ตัวอักษร",
                // "money.max" => "ต้องมีไม่เกิน10ตัวอักษร",
                // //pic
                // "pic.required" => "กรุณาอัพโหลดสลิปธนาคาร",
                // "pic.mimes" => "กรุณาอัพโหลดรูปที่มีนามสกุล (png, jpg, jpeg)",
            ],
        );
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
        ]);
        $username = User::find($data2->user_id)->username;
        event(new ShowBetsNow([
            "username" =>  $username,
            "number" => $data2->number,
            "money" => $data2->money,
            "status" => $data2->status,
        ]));
    }
}
