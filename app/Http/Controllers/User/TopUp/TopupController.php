<?php

namespace App\Http\Controllers\User\TopUp;

use App\Http\Controllers\Controller;
use App\Models\BankOrganization;
use App\Models\TopUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopupController extends Controller
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
        $admin_id = Auth::guard('user')->user()->admin_id;
        $bank_organizations = BankOrganization::where('admin_id', $admin_id)->get();
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
                "money" => "required|regex:/^\d+(\.\d{1,2})?$/|numeric|min:0|not_in:0",
            ],
            [
                //bank_or_id
                "bank_or_id.required" => "กรุณาเลือกธนาคารที่จะโอน",
                //number_account
                "number_account.required" => "กรุณากรอกช่องนี้",
                "number_account.max" => "ต้องมีไม่เกิน13ตัวอักษร",
                //name_account
                "name_account.required" => "กรุณากรอกช่องนี้",
                "name_account.min" => "ต้องมีอย่างน้อย2ตัวอักษร",
                "name_account.max" => "ต้องมีไม่เกิน255ตัวอักษร",
                //name_bank
                "name_bank.required" => "กรุณากรอกช่องนี้",
                "name_bank.min" => "ต้องมีอย่างน้อย2ตัวอักษร",
                "name_bank.max" => "ต้องมีไม่เกิน255ตัวอักษร",
                //image
                "image.required" => "กรุณากรอกช่องนี้",
                "image.mimes" => "ไฟล์ต้องนามสกุล png, jpg, jpeg เท่านั้น",
                //money
                "money.required" => "กรุณากรอกช่องนี้",
                "money.regex" => "กรุณากรอกให้เป็นในรูปแบบจำนวนเงิน",
                "money.min" => "ห้ามเป็นเลข0",
                "money.not_in" => "ห้ามเป็นเลข0",
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

    public function history()
    {
        $top_ups = TopUp::where('user_id', Auth::guard('user')->user()->id)->orderByDesc('updated_at')->paginate(10);
        return view('auth.user.top_up.history', compact('top_ups'));
    }
}
