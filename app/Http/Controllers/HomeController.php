<?php

namespace App\Http\Controllers;

use App\Models\BetDetail;
use App\Models\ConfigTurnOnTurnOff;
use App\Models\Result;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $config_turn_on_turn_off = ConfigTurnOnTurnOff::first();
        $results = Result::where('created_at', '>', Carbon::now()->subHours(1)->toDateTimeString())->orderBy('created_at', 'Asc')->get();
        $histories = BetDetail::where('created_at', '>', Carbon::now()->subHours(1)->toDateTimeString())
            ->orderByDesc('created_at')
            ->where('user_id', Auth::guard('user')->user()->id)
            ->get();
        // return   dd($config_turn_on_turn_off);
        return view('auth.user.home', compact('config_turn_on_turn_off', 'results', 'histories'));
    }
}
