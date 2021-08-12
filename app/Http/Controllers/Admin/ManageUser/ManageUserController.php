<?php

namespace App\Http\Controllers\Admin\ManageUser;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManageUserController extends Controller
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
        if (Auth::guard('admin')->user()->position == 0) {
            $users = DB::table('users')
                ->join('admins', 'users.admin_id', '=', 'admins.id')
                ->select('users.*', 'admins.username as admin_username')
                ->where('users.admin_id', Auth::guard('admin')->user()->id)
                ->orWhere('admins.admin_id', Auth::guard('admin')->user()->id)
                ->orderByDesc('created_at')
                ->paginate(10);
        } else {
            $users = User::where('admin_id', Auth::guard('admin')->user()->id)->orderByDesc('created_at')->paginate(10);
        }
        return view('auth.agent_and_admin.manage_user.manage_user', compact('users'));
    }

    public function get_agent()
    {
        $users = Admin::where('admin_id', Auth::guard('admin')->user()->id)->orWhere('id', Auth::guard('admin')->user()->id)->orderByDesc('created_at')->get();
        return response()->json(["data" => $users]);
    }

    public function store(Request $request)
    {
        if ($request->post_id != "") {
            $user = User::find($request->post_id);
            $request->validate(
                [
                    "name" => "required|min:2|max:255",
                    "username" => $user->username != $request->username ? "required|min:6|max:20|unique:users|unique:admins" : "required|min:6|max:20|unique:admins",
                    "password" =>  $request->password != null || $request->password != "" ? "required|min:8|max:20|confirmed" : "",
                    "telephone" => $user->telephone != $request->telephone ? "required|numeric|digits:10|unique:users|unique:admins" : "required|numeric|digits:10|unique:admins",
                    "money" => "required",
                    "admin_id" => "required",
                ],
                [
                    //name
                    "name.required" => "กรุณากรอกช่องนี้",
                    "name.min" => "กรุณากรอกให้ครบ 2 หลัก",
                    "name.max" => "กรุณากรอกไม่เกิน 255 หลัก",
                    //username
                    "username.required" => "กรุณากรอกช่องนี้",
                    "username.min" => "กรุณากรอกระหว่าง 8-20 หลัก",
                    "username.max" => "กรุณากรอกระหว่าง 8-20 หลัก",
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

                    //money
                    "money.required" => "กรุณากรอกช่องนี้",
                    "money.regex" => "กรุณากรอกให้เป็นในรูปแบบจำนวนเงิน",
                    //admin_id
                    "admin_id.required" => "กรุณาเลือกตัวแทน",
                ],
            );
            $user = User::updateOrCreate(['id' => $request->post_id], [
                "name" => $request->name,
                "username" => $request->username,
                "password" => $request->password != null || $request->password != "" ? bcrypt($request->password) : $user->password,
                "telephone" => $request->telephone,
                "money" => $request->money,
                "admin_id" => $request->admin_id,
            ]);
        } else {
            //เพิ่มข้อมูลใหม่
            $request->validate(
                [
                    "name" => "required|min:2|max:255",
                    "username" => "required|min:6|max:20|unique:users|unique:admins",
                    "password" => "required|min:8|max:20|confirmed",
                    "telephone" => "required|numeric|digits:10|unique:users|unique:admins",
                    "money" => "required|regex:/^\d+(\.\d{1,2})?$/",
                    "admin_id" => "required",
                ],
                [
                    //name
                    "name.required" => "กรุณากรอกช่องนี้",
                    "name.min" => "กรุณากรอกให้ครบ 2 หลัก",
                    "name.max" => "กรุณากรอกไม่เกิน 255 หลัก",
                    //username
                    "username.required" => "กรุณากรอกช่องนี้",
                    "username.min" => "กรุณากรอกระหว่าง 8-20 หลัก",
                    "username.max" => "กรุณากรอกระหว่าง 8-20 หลัก",
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
                    //money
                    "money.required" => "กรุณากรอกช่องนี้",
                    "money.regex" => "กรุณากรอกให้เป็นในรูปแบบจำนวนเงิน",
                    //admin_id
                    "admin_id.required" => "กรุณาเลือกตัวแทน",

                ],
            );
            $user = User::updateOrCreate(['id' => $request->post_id], [
                "name" => $request->name,
                "username" => $request->username,
                "password" => bcrypt($request->password),
                "telephone" => $request->telephone,
                "money" => $request->money,
                "admin_id" => $request->admin_id,
            ]);
        }
        $user->created_at_2 = Carbon::parse($user->created_at)->locale('th')->diffForHumans();
        $data = Admin::find($user->admin_id);
        $auth_position = Auth::guard('admin')->user()->position;

        return response()->json(['code' => '200', 'message' => 'บันทึกข้อมูลสำเร็จ', 'data' => $user, 'admin_data' => $data, "auth_position" => $auth_position], 200);
    }

    public function get_user($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    public function delete_post($id)
    {
        $user = User::find($id)->delete();
        return response()->json(['sucess' => "ลบข้อมูลเรียบร้อย", "code" => "200"]);
    }

    public function delete_all(Request $request)
    {
        $data = $request->pass;
        for ($i = 0; $i < count($data); $i++) {
            User::find($data[$i]['id'])->delete();
        }
        return response()->json(["code" => "200", "message" => "ลบข้อมูลสำเร็จ", "data" => $data]);
    }
}
