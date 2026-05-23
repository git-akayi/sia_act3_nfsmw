<?php

namespace App\Http\Controllers;

use App\Models\Race;
use App\Models\GarageCar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RaceController extends Controller
{
    public static function getRankThresholds(): array
    {
        return [
            15 => 0,
            14 => 50000,
            13 => 100000,
            12 => 180000,
            11 => 300000,
            10 => 500000,
            9  => 740000,
            8  => 1000000,
            7  => 1350000,
            6  => 1800000,
            5  => 2300000,
            4  => 3000000,
            3  => 4500000,
            2  => 6500000,
            1  => 10000000,
        ];
    }

    public static function getRankFromBounty(int $bounty): int
    {
        foreach (self::getRankThresholds() as $rank => $required) {
            if ($bounty >= $required) {
                return $rank;
            }
        }
        return 15;
    }

    public function index()
    {
        $user = Auth::user();
        $recentRaces = Race::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $myCar = GarageCar::where('user_id', $user->id)
            ->with('baseCar')
            ->first();

        return view('race.index', compact('user', 'recentRaces', 'myCar'));
    }

    public function race(Request $request)
    {
        $request->validate([
            'race_type' => 'required|in:Sprint,Circuit,Drag,Drift,Speedtrap,Knockout,Tollbooth',
        ]);

        $user = Auth::user();

        $myCar = GarageCar::where('user_id', $user->id)->first();

        if (!$myCar) {
            return back()->with('error', 'NO VEHICLE FOUND. ACQUIRE A CAR FIRST.');
        }

        // Calculate performance score
        $performance = $myCar->current_hp + ($myCar->current_torque * 0.5);

        // Specialty bonus — 20% boost if race type matches profile specialty
        if (strtolower($user->race_specialty) === strtolower($request->race_type)) {
            $performance *= 1.2;
        }

        // Race type bounty multipliers
        $bountyMultipliers = [
            'Sprint'    => 1.0,
            'Circuit'   => 1.3,
            'Drag'      => 0.8,
            'Drift'     => 1.5,
            'Speedtrap' => 1.2,
            'Knockout'  => 1.8,
            'Tollbooth' => 1.1,
        ];
        $multiplier = $bountyMultipliers[$request->race_type] ?? 1.0;

        // Generate random opponent difficulty
        $opponentDifficulty = rand(100, 600);

        // Determine result
        $result      = $performance > $opponentDifficulty ? 'WIN' : 'LOSS';
        $cashEarned  = 0;
        $bountyChange = 0;

        $oldRank = $user->blacklist_rank;

        if ($result === 'WIN') {
            $cashEarned   = rand(1000, 5000);
            $bountyChange = (int) (rand(300, 1500) * $multiplier);
            $user->bank_cash += $cashEarned;
            $user->bounty    += $bountyChange;
        } else {
            $bountyChange    = -(int) (rand(100, 400) * $multiplier);
            $user->bounty    = max(0, $user->bounty + $bountyChange);
        }

        // Auto-update blacklist rank based on new bounty
        $newRank = self::getRankFromBounty($user->bounty);
        $user->blacklist_rank = $newRank;
        $user->save();

        // Detect rank-up
        $rankedUp = $newRank < $oldRank;

        // Log the race
        Race::create([
            'user_id'             => $user->id,
            'race_type'           => $request->race_type,
            'performance_score'   => (int) $performance,
            'opponent_difficulty' => $opponentDifficulty,
            'result'              => $result,
            'cash_earned'         => $cashEarned,
            'bounty_change'       => $bountyChange,
        ]);

        return back()->with([
            'race_result'   => $result,
            'cash_earned'   => $cashEarned,
            'bounty_change' => $bountyChange,
            'performance'   => (int) $performance,
            'opponent'      => $opponentDifficulty,
            'race_type'     => $request->race_type,
            'ranked_up'     => $rankedUp,
            'new_rank'      => $newRank,
            'old_rank'      => $oldRank,
        ]);
    }
}