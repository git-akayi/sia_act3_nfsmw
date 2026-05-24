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

    // ── Web view ─────────────────────────────────────────────────────────────

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

        return view('races.index', compact('user', 'recentRaces', 'myCar'));
    }

    // ── Web POST (Blade form) ─────────────────────────────────────────────────

    public function race(Request $request)
    {
        $request->validate([
            'race_type' => 'required|in:Sprint,Circuit,Drag,Drift,Speedtrap,Knockout,Tollbooth',
        ]);

        $payload = $this->runRace($request->race_type, Auth::user());

        if (isset($payload['error'])) {
            return back()->with('error', $payload['error']);
        }

        return back()->with($payload);
    }

    // ── API POST (Flutter) ────────────────────────────────────────────────────

    public function apiRace(Request $request)
    {
        $request->validate([
            'race_type' => 'required|in:Sprint,Circuit,Drag,Drift,Speedtrap,Knockout,Tollbooth',
        ]);

        $payload = $this->runRace($request->race_type, $request->user());

        if (isset($payload['error'])) {
            return response()->json(['success' => false, 'message' => $payload['error']], 422);
        }

        return response()->json([
            'success'       => true,
            'result'        => $payload['race_result'],
            'race_type'     => $payload['race_type'],
            'your_score'    => $payload['performance'],
            'opponent'      => $payload['opponent'],
            'cash_earned'   => $payload['cash_earned'],
            'bounty_change' => $payload['bounty_change'],
            'ranked_up'     => $payload['ranked_up'],
            'new_rank'      => $payload['new_rank'],
            'old_rank'      => $payload['old_rank'],
        ]);
    }

    // ── API GET: recent race history (Flutter) ────────────────────────────────

    public function apiHistory(Request $request)
    {
        $races = Race::where('user_id', $request->user()->id)
            ->latest()
            ->take(10)
            ->get(['race_type', 'result', 'cash_earned', 'bounty_change', 'created_at']);

        return response()->json($races);
    }

    // ── Shared race logic ─────────────────────────────────────────────────────

    private function runRace(string $raceType, $user): array
    {
        $myCar = GarageCar::where('user_id', $user->id)->first();

        if (!$myCar) {
            return ['error' => 'NO VEHICLE FOUND. ACQUIRE A CAR FIRST.'];
        }

        // Performance score
        $performance = $myCar->current_hp + ($myCar->current_torque * 0.5);

        // Specialty bonus
        if (strtolower($user->race_specialty ?? '') === strtolower($raceType)) {
            $performance *= 1.2;
        }

        // Bounty multipliers per race type
        $bountyMultipliers = [
            'Sprint'    => 1.0,
            'Circuit'   => 1.3,
            'Drag'      => 0.8,
            'Drift'     => 1.5,
            'Speedtrap' => 1.2,
            'Knockout'  => 1.8,
            'Tollbooth' => 1.1,
        ];
        $multiplier = $bountyMultipliers[$raceType] ?? 1.0;

        // Random opponent
        $opponentDifficulty = rand(100, 600);

        $result       = $performance > $opponentDifficulty ? 'WIN' : 'LOSS';
        $cashEarned   = 0;
        $bountyChange = 0;
        $oldRank      = $user->blacklist_rank;

        if ($result === 'WIN') {
            $rankMultiplier = match(true) {
                $user->blacklist_rank <= 3  => 4.0,
                $user->blacklist_rank <= 6  => 3.0,
                $user->blacklist_rank <= 9  => 2.0,
                $user->blacklist_rank <= 12 => 1.5,
                default                     => 1.0,
            };
            $cashEarned   = (int)(rand(1000, 5000) * $rankMultiplier);
            $bountyChange = (int)(rand(900, 4500) * $multiplier);
            $user->cash   += $cashEarned;
            $user->bounty += $bountyChange;
        } else {
            $bountyChange = -(int)(rand(400, 1000) * $multiplier);
            $user->bounty = max(0, $user->bounty + $bountyChange);
        }

        // Auto-update blacklist rank
        $newRank              = self::getRankFromBounty($user->bounty);
        $user->blacklist_rank = $newRank;
        $user->save();

        $rankedUp = $newRank < $oldRank;

        // Log race
        Race::create([
            'user_id'             => $user->id,
            'race_type'           => $raceType,
            'performance_score'   => (int) $performance,
            'opponent_difficulty' => $opponentDifficulty,
            'result'              => $result,
            'cash_earned'         => $cashEarned,
            'bounty_change'       => $bountyChange,
        ]);

        return [
            'race_result'   => $result,
            'cash_earned'   => $cashEarned,
            'bounty_change' => $bountyChange,
            'performance'   => (int) $performance,
            'opponent'      => $opponentDifficulty,
            'race_type'     => $raceType,
            'ranked_up'     => $rankedUp,
            'new_rank'      => $newRank,
            'old_rank'      => $oldRank,
        ];
    }
}