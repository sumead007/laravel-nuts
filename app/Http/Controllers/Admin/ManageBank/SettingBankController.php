<?php

namespace App\Http\Controllers\Admin\ManageBank;

use App\Http\Controllers\Controller;
use App\Models\BankOrganization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingBankController extends Controller
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
        $bank_organizations = BankOrganization::where('admin_id', Auth::guard('admin')->user()->id)
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('auth.agent_and_admin.manage_bank.setting_my_bank', compact('bank_organizations'));
    }

    public function store(Request $request)
    {
        if ($request->post_id != "") {
            $data = BankOrganization::find($request->post_id);
            $request->validate(

                [
                    "number_account" => $data->number_account != $request->number_account ? "required|digits:10|unique:bank_organizations" : "required|digits:10",
                    "name_account" => "required|min:3|max:255",
                    "name_bank" => "required|min:3|max:255",
                ],
                [
                    //number_account
                    "number_account.required" => "กรุณากรอกช่องนี้",
                    "number_account.unique" => "เลขที่บัญชีนี้มีคนใช้แล้ว",
                    "number_account.digits" => "กรุณากรอกช่องนี้ 10 หลัก",
                    //name_account
                    "name_account.required" => "กรุณากรอกช่องนี้",
                    "name_account.min" => "กรุณากรอกระหว่าง 3-255 หลัก",
                    "name_account.max" => "กรุณากรอกระหว่าง 3-255 หลัก",
                    //name_bank
                    "name_bank.required" => "กรุณากรอกช่องนี้",
                    "name_bank.min" => "กรุณากรอกระหว่าง 3-255 หลัก",
                    "name_bank.max" => "กรุณากรอกระหว่าง 3-255 หลัก",
                ],
            );
            $user = BankOrganization::updateOrCreate(['id' => $request->post_id], [
                "number_account" => $request->number_account,
                "name_account" => $request->name_account,
                "name_bank" => $request->name_bank,
                "admin_id" => Auth::guard('admin')->user()->id,
            ]);
        } else {
            //เพิ่มข้อมูลใหม่
            $request->validate(
                [
                    "number_account" => "required|digits:10|unique:bank_organizations",
                    "name_account" => "required|min:3|max:255",
                    "name_bank" => "required|min:3|max:255",
                ],
                [
                    //number_account
                    "number_account.required" => "กรุณากรอกช่องนี้",
                    "number_account.unique" => "เลขที่บัญชีนี้มีคนใช้แล้ว",
                    "number_account.digits" => "กรุณากรอกช่องนี้ 10 หลัก",
                    //name_account
                    "name_account.required" => "กรุณากรอกช่องนี้",
                    "name_account.min" => "กรุณากรอกระหว่าง 3-255 หลัก",
                    "name_account.max" => "กรุณากรอกระหว่าง 3-255 หลัก",
                    //name_bank
                    "name_bank.required" => "กรุณากรอกช่องนี้",
                    "name_bank.min" => "กรุณากรอกระหว่าง 3-255 หลัก",
                    "name_bank.max" => "กรุณากรอกระหว่าง 3-255 หลัก",
                ],
            );
            $user = BankOrganization::updateOrCreate(['id' => $request->post_id], [
                "number_account" => $request->number_account,
                "name_account" => $request->name_account,
                "name_bank" => $request->name_bank,
                "admin_id" => Auth::guard('admin')->user()->id,
            ]);
        }
        $user->created_at_2 = Carbon::parse($user->created_at)->locale('th')->diffForHumans();

        return response()->json(['code' => '200', 'message' => 'บันทึกข้อมูลสำเร็จ', 'data' => $user], 200);
    }

    public function get_bank_organizations($id)
    {
        $data = BankOrganization::find($id);
        return response()->json($data);
    }

    public function delete_post($id)
    {
        $data = BankOrganization::find($id)->delete();
        return response()->json(['sucess' => "ลบข้อมูลเรียบร้อย", "code" => "200"]);
    }

    public function delete_all(Request $request)
    {
        $data = $request->pass;
        for ($i = 0; $i < count($data); $i++) {
            BankOrganization::find($data[$i]['id'])->delete();
        }
        return response()->json(["code" => "200", "message" => "ลบข้อมูลสำเร็จ", "data" => $data]);
    }
}
