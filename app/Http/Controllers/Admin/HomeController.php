<?php

namespace App\Http\Controllers\Admin;

use App\Events\Result;
use App\Events\TurnOnTurnOff;
use App\Http\Controllers\Controller;
use App\Models\Bet;
use App\Models\ConfigTurnOnTurnOff;
use App\Models\Result as ModelsResult;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $config_turn_on_turn_off = ConfigTurnOnTurnOff::first();
        $results = ModelsResult::where('created_at', '>', Carbon::now()->subHours(1)->toDateTimeString())->orderByDesc('created_at')->get();
        $bet_details = DB::table('bets')
            ->join('bet_details', 'bets.id', '=', 'bet_details.bet_id')
            ->join('users', 'bet_details.user_id', '=', 'users.id')
            ->select('bet_details.*', 'bets.time_open', 'users.username')
            ->where('time_open', $config_turn_on_turn_off->updated_at)
            ->get();
        // return dd($bet_details);
        return view('auth.agent_and_admin.home', compact('config_turn_on_turn_off', 'results', 'bet_details'));
    }

    public function turn_on_turn_off(Request $request)
    {
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
        $bet = Bet::orderBy('time_open', 'desc')->first();
        if ($bet->time_off == "" || $bet->time_off == null) {
            $config_turn_on_turn_off = ConfigTurnOnTurnOff::first();
            $bet->time_off = $config_turn_on_turn_off->updated_at;
            $bet->update();
            // return dd($bets);
            $data = ["result" => $request->value];
            $result = new ModelsResult();
            $result->result =  $request->value;
            $result->pic =  "ยังไม่มี";
            $result->bet_id =  $bet->id;
            $result->save();
            $data["created_at"] =  Carbon::parse($result->created_at)->locale('th')->diffForHumans();
            event(new Result($data));
        } else {
            return response()->json(['error' => 'Error msg'], 403); // Status code here
        }
        event(new TurnOnTurnOff(1));
    }
}
