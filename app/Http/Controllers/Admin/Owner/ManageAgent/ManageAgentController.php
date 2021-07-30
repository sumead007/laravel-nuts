<?php

namespace App\Http\Controllers\Admin\Owner\ManageAgent;

use App\Http\Controllers\Controller;
use App\Models\Admin;
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
        return view('auth.agent_and_admin.owner.manage_agent.home',compact('agents'));
    }
}
