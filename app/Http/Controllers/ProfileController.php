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
        $user->bounty = $request->bounty;
        $user->cars_owned = $request->cars_owned;
        $user->rivals_left = $request->rivals_left;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Updates internal game metrics plus custom ride and reppin' territory.
     */
    public function updateStats(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($request->filled('signature_car')) {
            $user->signature_car = $request->signature_car;
        }
        if ($request->filled('territory')) {
            $user->territory = $request->territory;
        }
        if ($request->filled('race_specialty')) {
            $user->race_specialty = $request->race_specialty;
        }

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

    public function index()
    {
        $myGarageCars = \App\Models\GarageCar::where('user_id', Auth::id())
                        ->with('baseCar')
                        ->get();

        $recentRaces = \App\Models\Race::where('user_id', Auth::id())
                        ->latest()
                        ->take(5)
                        ->get();

        return view('dashboard', compact('myGarageCars', 'recentRaces'));
    }
}