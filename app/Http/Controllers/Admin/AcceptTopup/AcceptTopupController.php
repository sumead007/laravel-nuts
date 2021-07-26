<?php

namespace App\Http\Controllers\Admin\AcceptTopup;

use App\Http\Controllers\Controller;
use App\Models\BankOrganization;
use App\Models\ConfirmTopup;
use App\Models\TopUp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcceptTopupController extends Controller
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
        $top_ups = TopUp::where('status', 0)->paginate(10);
        return view('auth.agent_and_admin.accept_topup.home', compact('top_ups'));
    }

    public function accept(Request $request)
    {
        if ($request->type == 0) {
            $user = User::find($request->cust_id);
            $top_up = TopUp::find($request->id);


            $money_tran = floatval($top_up->money);
            $money_user = floatval($user->money);
            $total_money = $money_user + $money_tran;
            // return dd($total_money);

            User::updateOrCreate(['id' => $request->cust_id], [
                "money" => $total_money,
            ]);
            $data = TopUp::updateOrCreate(['id' => $request->id], [
                "status" => "1",
            ]);

            ConfirmTopup::updateOrCreate(['id' => ""], [
                "topup_id" => $request->id,
                "admin_id" => Auth::guard('admin')->user()->id,
                "note" => "-",
            ]);
        } else {
            $data = TopUp::updateOrCreate(['id' => $request->id], [
                "status" => "2",
            ]);

            ConfirmTopup::updateOrCreate(['id' => ""], [
                "topup_id" => $request->id,
                "admin_id" => Auth::guard('admin')->user()->id,
                "note" => $request->note,
            ]);
        }

        return response()->json(['code' => '200', 'message' => 'บันทึกข้อมูลสำเร็จ', 'data' => $data], 200);
    }

    public function store_selection(Request $request)
    {
        if ($request->type == 0) {
            for ($i = 0; $i < count($request->pass); $i++) {
                $user = User::find($request->pass[$i]['cusm_id']);
                $top_up = TopUp::find($request->pass[$i]['id']);
                $money_tran = floatval($top_up->money);
                $money_user = floatval($user->money);
                $total_money = $money_user + $money_tran;

                User::updateOrCreate(['id' => $request->pass[$i]['cusm_id']], [
                    "money" => $total_money,
                ]);
                $data = TopUp::updateOrCreate(['id' => $request->pass[$i]['id']], [
                    "status" => "1",
                ]);

                ConfirmTopup::updateOrCreate(['id' => ""], [
                    "topup_id" => $request->pass[$i]['id'],
                    "admin_id" => Auth::guard('admin')->user()->id,
                    "note" => "-",
                ]);
            }
        } else {
            for ($i = 0; $i < count($request->pass); $i++) {
                $data = TopUp::updateOrCreate(['id' => $request->pass[$i]['id']], [
                    "status" => "2",
                ]);

                ConfirmTopup::updateOrCreate(['id' => ""], [
                    "topup_id" => $request->pass[$i]['id'],
                    "admin_id" => Auth::guard('admin')->user()->id,
                    "note" => "-",
                ]);
            }
        }
        return response()->json(["code" => "200", "message" => "ทำรายการสำเร็จ", "data" => $request->pass]);
    }
}
