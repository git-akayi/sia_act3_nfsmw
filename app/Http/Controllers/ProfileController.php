<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        $user->blacklist_rank = $request->blacklist_rank;
        $user->bounty         = $request->bounty;
        $user->cars_owned     = $request->cars_owned;
        $user->rivals_left    = $request->rivals_left;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updateStats(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($request->filled('signature_car'))  $user->signature_car  = $request->signature_car;
        if ($request->filled('territory'))       $user->territory      = $request->territory;
        if ($request->filled('race_specialty'))  $user->race_specialty = $request->race_specialty;

        $user->save();

        return back()->with('success', 'PROFILE UPDATED.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    // ── Web dashboard ─────────────────────────────────────────────────────

    public function index()
    {
        $user = Auth::user();

        // ✅ Auto-correct rank in case it's out of sync with bounty + beaten bosses
        $beaten      = $user->blacklist_beaten ?? [];
        $correctRank = RaceController::getRankFromBounty($user->bounty, $beaten);
        if ($user->blacklist_rank !== $correctRank) {
            $user->blacklist_rank = $correctRank;
            $user->save();
            $user->refresh();
        }

        $myGarageCars = \App\Models\GarageCar::where('user_id', $user->id)
                            ->with('baseCar')
                            ->get();

        $recentRaces = \App\Models\Race::where('user_id', $user->id)
                            ->latest()
                            ->take(5)
                            ->get();

        return view('dashboard', compact('myGarageCars', 'recentRaces'));
    }

    // ── API endpoints ─────────────────────────────────────────────────────

    public function apiDashboard(Request $request)
    {
        $user = $request->user();

        // ✅ Auto-correct rank for Flutter too
        $beaten      = $user->blacklist_beaten ?? [];
        $correctRank = RaceController::getRankFromBounty($user->bounty, $beaten);
        if ($user->blacklist_rank !== $correctRank) {
            $user->blacklist_rank = $correctRank;
            $user->save();
            $user->refresh();
        }

        $garage = \App\Models\GarageCar::where('user_id', $user->id)
                    ->with('baseCar')
                    ->get()
                    ->map(function ($gc) {
                        return [
                            'id'                    => $gc->id,
                            'make_model'            => $gc->baseCar->make_model ?? 'Unknown',
                            'current_hp'            => $gc->current_hp,
                            'current_torque'        => $gc->current_torque,
                            'mechanical_efficiency' => $gc->mechanical_efficiency,
                            'calculated_valuation'  => $gc->calculated_valuation,
                        ];
                    });

        return response()->json([
            'user'   => $user,
            'garage' => $garage,
        ]);
    }

    public function apiUpdateProfile(Request $request)
    {
        $user = $request->user();

        if ($request->filled('signature_car'))  $user->signature_car  = $request->signature_car;
        if ($request->filled('territory'))       $user->territory      = $request->territory;
        if ($request->filled('race_specialty'))  $user->race_specialty = $request->race_specialty;

        $user->save();

        return response()->json(['user' => $user->fresh()]);
    }
}