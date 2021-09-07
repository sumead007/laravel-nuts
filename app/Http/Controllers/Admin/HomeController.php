<?php

namespace App\Http\Controllers\Admin;

use App\Events\Result;
use App\Events\TurnOnTurnOff;
use App\Http\Controllers\Controller;
use App\Models\Bet;
use App\Models\BetDetail;
use App\Models\ConfigTurnOnTurnOff;
use App\Models\Result as ModelsResult;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
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
        $config_turn_on_turn_off = ConfigTurnOnTurnOff::first();
        $results = ModelsResult::where('created_at', '>', Carbon::now()->subHours(1)->toDateTimeString())
            ->orderBy('created_at', 'asc')
            ->get();
        $bet_details = DB::table('bets')
            ->join('bet_details', 'bets.id', '=', 'bet_details.bet_id')
            ->join('users', 'bet_details.user_id', '=', 'users.id')
            ->select('bet_details.*', 'bets.time_open', 'users.username')
            ->where('time_open', $config_turn_on_turn_off->updated_at)
            ->orderByDesc('bet_details.created_at')
            ->get();
        // return dd($bet_details);
        for ($i = 0; $i < count($results); $i++) {
            $results[$i]->path1 = $this->getpath1($results[$i]->pic1);
            $results[$i]->path2 = $this->getpath2($results[$i]->pic2);
            $results[$i]->path3 = $this->getpath3($results[$i]->pic3);
        }

        // return dd($results);
        return view('auth.agent_and_admin.home', compact('config_turn_on_turn_off', 'results', 'bet_details'));
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

    public function turn_on_turn_off(Request $request)
    {
        $bet_details = BetDetail::where('status', 0);
        if (count($bet_details->get()) > 0 && $request->value == 0) {

            return response()->json(["message" => "มีผู้เล่นแทงเข้ามาแล้ว กรุณาออกผลก่อน"], 402);

            //ยกเลิกและคืนเงิน
            // $bet_details1 = $bet_details->get();
            // $bet_details->update(['status' => 3]);
            // // return dd($bet_details1);
            // $config_turn_on_turn_off = ConfigTurnOnTurnOff::first();
            // $bet = Bet::find($bet_details1[0]->bet_id)->update([
            //     "time_off" => $config_turn_on_turn_off->updated_at
            // ]);

            // for ($i = 0; $i < count($bet_details1); $i++) {
            //     $user = User::find($bet_details1[$i]->user_id);
            //     $user->money += $bet_details1[$i]->money;
            //     $user->update();
            // }
        }
        $user = ConfigTurnOnTurnOff::updateOrCreate(['id' => 1], [
            "status" => $request->value,
        ]);
        event(new TurnOnTurnOff($user->status));
    }

    public function get_status()
    {
        $config_turn_on_turn_off = ConfigTurnOnTurnOff::first();
        return response()->json($config_turn_on_turn_off);
    }

    public function result(Request $request)
    {
        // return dd(asset('images/dice/1.jpg'));
        $bet = Bet::orderBy('time_open', 'desc')->first();
        if ($bet->time_off == "" || $bet->time_off == null) {
            $config_turn_on_turn_off = ConfigTurnOnTurnOff::first();
            $bet->time_off = $config_turn_on_turn_off->updated_at;
            $bet->update();
            // return dd($bets);
            $data = ["result" => $request->value];
            $result = new ModelsResult();
            $result->result =  $request->value;
            $result->pic1 =  $request->input1;
            $result->pic2 =  $request->input2;
            $result->pic3 =  $request->input3;
            $result->bet_id =  $bet->id;
            $result->save();
            $data["created_at"] =  Carbon::parse($result->created_at)->locale('th')->diffForHumans();
            $result->path1 = asset($this->getpath1($result->pic1));
            $result->path2 = asset($this->getpath2($result->pic2));
            $result->path3 = asset($this->getpath3($result->pic3));

            event(new Result($result));
            $bet_details1 = BetDetail::where('bet_id', $bet->id)->where('number', $request->value);
            $bet_details1->update(['status' => 2]);
            $bet_details2 = BetDetail::where('bet_id', $bet->id)->where('number', '!=', $request->value);
            $bet_details2->update(['status' => 1]);
            // return dd($bet_details1->get());
            $bet_details2 = $bet_details2->get();
            for ($i = 0; $i < count($bet_details2); $i++) {
                $user = User::find($bet_details2[$i]->user_id);
                $user->money += ($bet_details2[$i]->money_deducted_first + $bet_details2[$i]->money);
                $user->update();
            }
            event(new TurnOnTurnOff(1));
        } else {
            return response()->json(['error' => 'Error msg'], 403); // Status code here
        }
    }
}
