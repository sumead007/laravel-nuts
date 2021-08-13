<?php

namespace App\Http\Controllers\Admin\Owner\ManageAgent;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManageAgentController extends Controller
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
        $agents = Admin::where('admin_id', Auth::guard('admin')->user()->id)
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('auth.agent_and_admin.owner.manage_agent.home', compact('agents'));
    }

    public function store(Request $request)
    {
        if ($request->post_id != "") {
            $admin = Admin::find($request->post_id);
            $request->validate(
                [
                    "name" => "required|min:2|max:255",
                    "username" => $admin->username != $request->username ? "required|min:6|max:20|unique:users|unique:admins" : "required|min:6|max:20|unique:users",
                    "password" =>  $request->password != null || $request->password != "" ? "required|min:8|max:20|confirmed" : "",
                    "telephone" => $admin->telephone != $request->telephone ? "required|numeric|digits:10|unique:users|unique:admins" : "required|numeric|digits:10|unique:users",
                    "credit" => "required|numeric",
                    "share_percentage" => "required||between:0,99.99",
                ],
                [
                    //name
                    "name.required" => "กรุณากรอกช่องนี้",
                    "name.min" => "กรุณากรอกให้ครบ 2-255 หลัก",
                    "name.max" => "กรุณากรอกไม่เกิน 2-255 หลัก",
                    //username
                    "username.required" => "กรุณากรอกช่องนี้",
                    "username.min" => "กรุณากรอกระหว่าง 6-20 หลัก",
                    "username.max" => "กรุณากรอกระหว่าง 6-20 หลัก",
                    "username.unique" => "ชื่อผู้ใช้นี้มีคนใช้แล้ว",
                    //password
                    "password.required" => "กรุณากรอกช่องนี้",
                    "password.min" => "กรุณากรอกระหว่าง 8-20 หลัก",
                    "password.max" => "กรุณากรอกระหว่าง 8-20 หลัก",
                    "password.confirmed" => "กรุณายืนยันรหัสผ่านให้ถูกต้อง",
                    //telephone
                    "telephone.required" => "กรุณากรอกช่องนี้",
                    "telephone.numeric" => "กรุณากรอกเป็นตัวเลข",
                    "telephone.digits" => "กรุณากรอกให้ครบ 10 หลัก",
                    "telephone.unique" => "เบอร์โทรนี้มีผู้คนใช้งานแล้ว",
                    //credit
                    "credit.required" => "กรุณากรอกช่องนี้",
                    "credit.numeric" => "กรุณากรอกเป็นตัวเลข",
                    //share_percentage
                    "share_percentage.required" => "กรุณาเลือกตัวแทน",
                    "share_percentage.regex" => "กรุณากรอกตัวเลขระหว่าง 0 - 99.9 เท่านั้น",
                ],
            );
            $user = Admin::updateOrCreate(['id' => $request->post_id], [
                "name" => $request->name,
                "username" => $request->username,
                "password" => $request->password != null || $request->password != "" ? bcrypt($request->password) : $admin->password,
                "telephone" => $request->telephone,
                "credit" => $request->credit,
                "share_percentage" => $request->share_percentage,
                "position" => 1,
            ]);
        } else {
            //เพิ่มข้อมูลใหม่
            $request->validate(
                [
                    "name" => "required|min:2|max:255",
                    "username" => "required|min:6|max:20|unique:users|unique:admins",
                    "password" => "required|min:8|max:20|confirmed",
                    "telephone" => "required|numeric|digits:10|unique:users|unique:admins",
                    "credit" => "required|numeric",
                    "share_percentage" => "required|between:0,99.99",
                ],
                [
                    //name
                    "name.required" => "กรุณากรอกช่องนี้",
                    "name.min" => "กรุณากรอกให้ครบ 2 หลัก",
                    "name.max" => "กรุณากรอกไม่เกิน 255 หลัก",
                    //username
                    "username.required" => "กรุณากรอกช่องนี้",
                    "username.min" => "กรุณากรอกระหว่าง 6-20 หลัก",
                    "username.max" => "กรุณากรอกระหว่าง 6-20 หลัก",
                    "username.unique" => "ชื่อผู้ใช้นี้มีคนใช้แล้ว",
                    //password
                    "password.required" => "กรุณากรอกช่องนี้",
                    "password.min" => "กรุณากรอกระหว่าง 8-20 หลัก",
                    "password.max" => "กรุณากรอกระหว่าง 8-20 หลัก",
                    "password.confirmed" => "กรุณายืนยันรหัสผ่านให้ถูกต้อง",
                    //telephone
                    "telephone.required" => "กรุณากรอกช่องนี้",
                    "telephone.numeric" => "กรุณากรอกเป็นตัวเลข",
                    "telephone.digits" => "กรุณากรอกให้ครบ 10 หลัก",
                    "telephone.unique" => "เบอร์โทรนี้มีผู้คนใช้งานแล้ว",
                    //credit
                    "credit.required" => "กรุณากรอกช่องนี้",
                    "credit.numeric" => "กรุณากรอกเป็นตัวเลข",
                    //share_percentage
                    "share_percentage.required" => "กรุณาเลือกตัวแทน",
                    "share_percentage.regex" => "กรุณากรอกตัวเลขระหว่าง 0 - 99.9 เท่านั้น",
                ],
            );
            $user = Admin::updateOrCreate(['id' => $request->post_id], [
                "name" => $request->name,
                "username" => $request->username,
                "password" => bcrypt($request->password),
                "telephone" => $request->telephone,
                "credit" => $request->credit,
                "share_percentage" => $request->share_percentage,
                "position" => 1,
                "admin_id" => Auth::guard('admin')->user()->id
            ]);
        }
        $user->created_at_2 = Carbon::parse($user->created_at)->locale('th')->diffForHumans();


        return response()->json(['code' => '200', 'message' => 'บันทึกข้อมูลสำเร็จ', 'data' => $user], 200);
    }

    public function get_agent($id)
    {
        $admin = Admin::find($id);
        return response()->json($admin);
    }

    public function delete_post($id)
    {
        $user = Admin::find($id)->delete();
        return response()->json(['sucess' => "ลบข้อมูลเรียบร้อย", "code" => "200"]);
    }

    public function delete_all(Request $request)
    {
        $data = $request->pass;
        for ($i = 0; $i < count($data); $i++) {
            Admin::find($data[$i]['id'])->delete();
        }
        return response()->json(["code" => "200", "message" => "ลบข้อมูลสำเร็จ", "data" => $data]);
    }
}
