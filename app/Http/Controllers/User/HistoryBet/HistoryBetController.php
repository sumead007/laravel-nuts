<?php

namespace App\Http\Controllers\User\HistoryBet;

use App\Http\Controllers\Controller;
use App\Models\BetDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryBetController extends Controller
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
        $histories = BetDetail::where('user_id', Auth::guard('user')->user()->id)
            ->orderByDesc('created_at')->get();
        return view('auth.user.history_bet.home', compact('histories'));
    }
}
