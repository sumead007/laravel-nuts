<?php

namespace App\Http\Controllers\User\TopUp;

use App\Http\Controllers\Controller;
use App\Models\BankOrganization;
use App\Models\TopUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopupController extends Controller
{
    public function index()
    {
        $agent_id = Auth::guard('user')->user()->agent_id;
        $admin_id = Auth::guard('user')->user()->admin_id;
        if ($agent_id != null) {
            $bank_organizations = BankOrganization::where('agent_id', $agent_id)->get();
        } else {
            $bank_organizations = BankOrganization::where('admin_id', $admin_id)->get();
        }
        // return dd($bank_organizations);
        return view('auth.user.top_up.home', compact('bank_organizations'));
    }

    public function store(Request $request)
    {
        //เพิ่มข้อมูลใหม่
        $request->validate(
            [
                "bank_or_id" => "required",
                "number_account" => "required|max:13",
                "name_account" => "required|min:2|max:255",
                "name_bank" => "required|min:2|max:255",
                "image" => "required|mimes:png,jpg,jpeg",
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

        //การเข้ารหัสรูปภาพ
        $service_image = $request->file('image');
        //genarate ชื่อภาพ
        $name_gen = hexdec(uniqid());
        //ดึงนามสกุลรูป
        $img_ext = strtolower($service_image->getClientOriginalExtension());
        $img_name = $name_gen . '.' . $img_ext;

        $upload_location = "images/services/top_up/receipt/";
        $full_path = $upload_location . $img_name;
        // return dd($request->bank_cus_id);
        // $bank_customer = BankCustomer::find($request->bank_cus_id);
        $user = TopUp::updateOrCreate(['id' => ""], [
            "name_account" => $request->name_account,
            "name_bank" => $request->name_bank,
            "number_account" => $request->number_account,
            "image" => $full_path,
            "money" => $request->money,
            "status" => 0,
            "bank_or_id" => $request->bank_or_id,
            "user_id" => Auth::guard('user')->user()->id,
        ]);

        $service_image->move($upload_location, $img_name);

        return response()->json(['code' => '200', 'message' => 'บันทึกข้อมูลสำเร็จ', 'data' => $user], 200);
    }
}
