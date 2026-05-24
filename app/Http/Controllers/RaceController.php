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

    /**
     * Fixed difficulty score per blacklist rank boss.
     * Scales from easy (rank 14) to brutal (rank 1).
     */
    public static function getBlacklistBossDifficulty(): array
    {
        return [
            14 => 220,
            13 => 300,
            12 => 380,
            11 => 460,
            10 => 540,
            9  => 620,
            8  => 700,
            7  => 780,
            6  => 860,
            5  => 940,
            4  => 1020,
            3  => 1100,
            2  => 1180,
            1  => 1260,
        ];
    }

    /**
     * Determine rank from bounty, but cap at the next rank
     * if the blacklist race for that rank hasn't been beaten yet.
     */
    public static function getRankFromBounty(int $bounty, array $blacklistBeaten = []): int
    {
        $thresholds = self::getRankThresholds();

        // ✅ Find highest qualifying rank (lowest number)
        // by iterating from rank 1 upward and stopping at first match
        $earnedRank = 15;
        foreach ($thresholds as $rank => $required) {
            if ($bounty >= $required && $rank < $earnedRank) {
                $earnedRank = $rank;
            }
        }

        // Walk back up if blacklist boss not beaten
        $currentRank = $earnedRank;
        while ($currentRank < 15 && !in_array($currentRank, $blacklistBeaten)) {
            $currentRank++;
        }

        return $currentRank;
    }
    /**
     * Check if a blacklist race is available for the user.
     * Available when: bounty >= threshold for next rank AND that rank's boss not beaten yet.
     */
    public static function getAvailableBlacklistRace($user): ?int
    {
        $beaten     = $user->blacklist_beaten ?? [];
        $thresholds = self::getRankThresholds();
        $currentRank = $user->blacklist_rank;

        // The next rank to unlock is currentRank - 1
        $targetRank = $currentRank - 1;
        if ($targetRank < 1) return null; // Already #1

        $requiredBounty = $thresholds[$targetRank] ?? null;
        if ($requiredBounty === null) return null;

        // Bounty threshold met and boss not beaten yet
        if ($user->bounty >= $requiredBounty && !in_array($targetRank, $beaten)) {
            return $targetRank;
        }

        return null;
    }

    /**
     * Tiered normal opponent difficulty by rank.
     */
    private function getOpponentDifficulty(int $rank): int
    {
        $range = match (true) {
            $rank >= 14 => [155, 255],
            $rank >= 12 => [195, 315],
            $rank >= 10 => [255, 395],
            $rank >= 8  => [325, 475],
            $rank >= 6  => [395, 555],
            $rank >= 4  => [475, 635],
            $rank >= 2  => [555, 715],
            default     => [635, 795],
        };

        return rand($range[0], $range[1]);
    }

    // ── Web view ─────────────────────────────────────────────────────────────

    public function index()
    {
        $user = Auth::user();

        // ✅ Auto-correct rank in case it's out of sync
        $beaten  = $user->blacklist_beaten ?? [];
        $correct = self::getRankFromBounty($user->bounty, $beaten);
        if ($user->blacklist_rank !== $correct) {
            $user->blacklist_rank = $correct;
            $user->save();
        }

        $recentRaces = Race::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $myCar = GarageCar::where('user_id', $user->id)
            ->whereHas('baseCar', function ($q) use ($user) {
                $q->where('make_model', $user->signature_car);
            })
            ->with('baseCar')
            ->first()
            ?? GarageCar::where('user_id', $user->id)
            ->with('baseCar')
            ->first();

        $blacklistRaceAvailable = self::getAvailableBlacklistRace($user);

        return view('race.index', compact('user', 'recentRaces', 'myCar', 'blacklistRaceAvailable'));
    }
    // ── Web POST: normal race (Blade) ─────────────────────────────────────────

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

    // ── Web POST: blacklist race (Blade) ──────────────────────────────────────

    public function blacklistRace(Request $request)
    {
        $payload = $this->runBlacklistRace(Auth::user());

        if (isset($payload['error'])) {
            return back()->with('error', $payload['error']);
        }

        return back()->with($payload);
    }

    // ── API POST: normal race (Flutter) ──────────────────────────────────────

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
            'success'                 => true,
            'result'                  => $payload['race_result'],
            'race_type'               => $payload['race_type'],
            'your_score'              => $payload['performance'],
            'opponent'                => $payload['opponent'],
            'cash_earned'             => $payload['cash_earned'],
            'bounty_change'           => $payload['bounty_change'],
            'ranked_up'               => $payload['ranked_up'],
            'new_rank'                => $payload['new_rank'],
            'old_rank'                => $payload['old_rank'],
            'blacklist_race_available' => $payload['blacklist_race_available'],
        ]);
    }

    // ── API POST: blacklist race (Flutter) ────────────────────────────────────

    public function apiBlacklistRace(Request $request)
    {
        $payload = $this->runBlacklistRace($request->user());

        if (isset($payload['error'])) {
            return response()->json(['success' => false, 'message' => $payload['error']], 422);
        }

        return response()->json([
            'success'       => true,
            'result'        => $payload['race_result'],
            'race_type'     => 'Blacklist',
            'your_score'    => $payload['performance'],
            'opponent'      => $payload['opponent'],
            'cash_earned'   => $payload['cash_earned'],
            'bounty_change' => $payload['bounty_change'],
            'ranked_up'     => $payload['ranked_up'],
            'new_rank'      => $payload['new_rank'],
            'old_rank'      => $payload['old_rank'],
            'boss_rank'     => $payload['boss_rank'],
        ]);
    }

    // ── API GET: race history (Flutter) ──────────────────────────────────────

    public function apiHistory(Request $request)
    {
        $races = Race::where('user_id', $request->user()->id)
            ->latest()
            ->take(10)
            ->get(['race_type', 'result', 'cash_earned', 'bounty_change', 'created_at']);

        return response()->json($races);
    }

    // ── API GET: blacklist race status (Flutter) ──────────────────────────────

   public function apiBlacklistStatus(Request $request)
{
    $user      = $request->user();
    $available = self::getAvailableBlacklistRace($user);
    $bosses    = self::getBlacklistBossDifficulty();

    // Calculate player's current performance score (same formula as runRace)
    $myCar = GarageCar::where('user_id', $user->id)
        ->whereHas('baseCar', function ($q) use ($user) {
            $q->where('make_model', $user->signature_car);
        })
        ->with('baseCar')
        ->first()
        ?? GarageCar::where('user_id', $user->id)->with('baseCar')->first();

    $yourScore = $myCar
        ? (int)($myCar->current_hp + ($myCar->current_torque * 0.5))
        : null;

    return response()->json([
        'blacklist_race_available' => $available !== null,
        'target_rank'             => $available,
        'boss_difficulty'         => $available ? $bosses[$available] : null,
        'your_score'              => $yourScore,
        'blacklist_beaten'        => $user->blacklist_beaten ?? [],
    ]);
}

    // ── Shared: normal race logic ─────────────────────────────────────────────

    private function runRace(string $raceType, $user): array
    {
        $myCar = GarageCar::where('user_id', $user->id)
            ->whereHas('baseCar', function ($q) use ($user) {
                $q->where('make_model', $user->signature_car);
            })
            ->with('baseCar')
            ->first()
            ?? GarageCar::where('user_id', $user->id)->with('baseCar')->first();

        if (!$myCar) {
            return ['error' => 'NO VEHICLE FOUND. ACQUIRE A CAR FIRST.'];
        }

        $performance = $myCar->current_hp + ($myCar->current_torque * 0.5);

        if (strtolower($user->race_specialty ?? '') === strtolower($raceType)) {
            $performance *= 1.2;
        }

        $bountyMultipliers = [
            'Sprint'    => 1.2,
            'Circuit'   => 1.3,
            'Drag'      => 0.9,
            'Drift'     => 1.5,
            'Speedtrap' => 1.3,
            'Knockout'  => 1.5,
            'Tollbooth' => 1.1,
        ];
        $multiplier = $bountyMultipliers[$raceType] ?? 1.0;

        $opponentDifficulty = $this->getOpponentDifficulty($user->blacklist_rank);
        $result             = $performance > $opponentDifficulty ? 'WIN' : 'LOSS';
        $cashEarned         = 0;
        $bountyChange       = 0;
        $oldRank            = $user->blacklist_rank;
        $beaten             = $user->blacklist_beaten ?? [];

        if ($result === 'WIN') {
            $rankMultiplier = match (true) {
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

        // Rank is gated by blacklist races
        $newRank              = self::getRankFromBounty($user->bounty, $beaten);
        $user->blacklist_rank = $newRank;
        $user->save();

        $rankedUp = $newRank < $oldRank;

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
            'race_result'             => $result,
            'cash_earned'             => $cashEarned,
            'bounty_change'           => $bountyChange,
            'performance'             => (int) $performance,
            'opponent'                => $opponentDifficulty,
            'race_type'               => $raceType,
            'ranked_up'               => $rankedUp,
            'new_rank'                => $newRank,
            'old_rank'                => $oldRank,
            'blacklist_race_available' => self::getAvailableBlacklistRace($user) !== null,
        ];
    }

    // ── Shared: blacklist race logic ──────────────────────────────────────────

    private function runBlacklistRace($user): array
    {
        $myCar = GarageCar::where('user_id', $user->id)->first();

        if (!$myCar) {
            return ['error' => 'NO VEHICLE FOUND. ACQUIRE A CAR FIRST.'];
        }

        $targetRank = self::getAvailableBlacklistRace($user);

        if ($targetRank === null) {
            return ['error' => 'NO BLACKLIST RACE AVAILABLE. BUILD YOUR BOUNTY FIRST.'];
        }

        $bossDifficulties   = self::getBlacklistBossDifficulty();
        $opponentDifficulty = $bossDifficulties[$targetRank];

        $performance = $myCar->current_hp + ($myCar->current_torque * 0.5);

        // Specialty bonus applies here too
        $result       = $performance > $opponentDifficulty ? 'WIN' : 'LOSS';
        $cashEarned   = 0;
        $bountyChange = 0;
        $oldRank      = $user->blacklist_rank;
        $beaten       = $user->blacklist_beaten ?? [];

        if ($result === 'WIN') {
            // Big reward: 2.5x bounty multiplier + large cash prize
            $rankMultiplier = match (true) {
                $targetRank <= 3  => 4.0,
                $targetRank <= 6  => 3.0,
                $targetRank <= 9  => 2.0,
                $targetRank <= 12 => 1.5,
                default           => 1.0,
            };
            $cashEarned   = (int)(rand(8000, 20000) * $rankMultiplier); // big cash prize
            $bountyChange = (int)(rand(2000, 6000) * 2.5);              // 2.5x bounty bonus
            $user->cash   += $cashEarned;
            $user->bounty += $bountyChange;

            // Mark boss as beaten and unlock the rank
            $beaten[] = $targetRank;
            $user->blacklist_beaten = array_values(array_unique($beaten));
        } else {
            // Loss: normal penalty, boss stays available
            $bountyChange = -(int)(rand(600, 1500));
            $user->bounty = max(0, $user->bounty + $bountyChange);
        }

        // Recalculate rank with updated beaten list
        $newRank              = self::getRankFromBounty($user->bounty, $user->blacklist_beaten ?? []);
        $user->blacklist_rank = $newRank;
        $user->save();

        $rankedUp = $newRank < $oldRank;

        Race::create([
            'user_id'             => $user->id,
            'race_type'           => 'Blacklist',
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
            'ranked_up'     => $rankedUp,
            'new_rank'      => $newRank,
            'old_rank'      => $oldRank,
            'boss_rank'     => $targetRank,
        ];
    }
}
