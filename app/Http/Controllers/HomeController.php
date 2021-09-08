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
        $results = Result::where('created_at', '>', Carbon::now()->subHours(1)->toDateTimeString())
            ->orderBy('created_at', 'Asc')
            ->get();
        $histories = BetDetail::where([
            ['created_at', '>', Carbon::now()->subHours(1)->toDateTimeString()],
            ['user_id', Auth::guard('user')->user()->id]
        ])
            ->orWhere('status', 0)
            ->orderByDesc('created_at')
            ->get();
        for ($i = 0; $i < count($results); $i++) {
            $results[$i]->path1 = $this->getpath1($results[$i]->pic1);
            $results[$i]->path2 = $this->getpath2($results[$i]->pic2);
            $results[$i]->path3 = $this->getpath3($results[$i]->pic3);
        }
        // return   dd($config_turn_on_turn_off);
        return view('auth.user.home', compact('config_turn_on_turn_off', 'results', 'histories'));
    }

    public function getpath1($data)
    {
        $path = null;
        if ($data == 1) {
            $path = "images/dice/1.jpg";
        } else if ($data == 2) {
            $path = "images/dice/2.jpg";
        } else if ($data == 3) {
            $path = "images/dice/3.jpg";
        } else if ($data == 4) {
            $path = "images/dice/4.jpg";
        } else if ($data == 5) {
            $path = "images/dice/5.jpg";
        } else {
            $path = "images/dice/6.jpg";
        }
        return $path;
    }

    public function getpath2($data)
    {
        $path = null;
        if ($data == 1) {
            $path = "images/dice/1.jpg";
        } else if ($data == 2) {
            $path = "images/dice/2.jpg";
        } else if ($data == 3) {
            $path = "images/dice/3.jpg";
        } else if ($data == 4) {
            $path = "images/dice/4.jpg";
        } else if ($data == 5) {
            $path = "images/dice/5.jpg";
        } else {
            $path = "images/dice/6.jpg";
        }
        return $path;
    }

    public function getpath3($data)
    {
        $path = null;
        if ($data == 1) {
            $path = "images/dice/1.jpg";
        } else if ($data == 2) {
            $path = "images/dice/2.jpg";
        } else if ($data == 3) {
            $path = "images/dice/3.jpg";
        } else if ($data == 4) {
            $path = "images/dice/4.jpg";
        } else if ($data == 5) {
            $path = "images/dice/5.jpg";
        } else {
            $path = "images/dice/6.jpg";
        }
        return $path;
    }
}
