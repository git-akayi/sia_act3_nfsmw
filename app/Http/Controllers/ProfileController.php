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
    $request->validate([
        'blacklist_rank' => ['required', 'integer'],
        'bounty'         => ['required', 'integer'],
        'signature_car'  => ['required', 'string'],
        'territory'      => ['required', 'string'],
        'race_specialty' => ['required', 'string'],
        'cars_owned'     => ['required', 'integer'],
    ]);

    $data = $request->only([
        'blacklist_rank', 'bounty', 'signature_car', 'territory', 'race_specialty', 'cars_owned'
    ]);

    // GUARD: If they are NOT Rank #1, force the specialty back to 'Sprint'
    if ($data['blacklist_rank'] != 1 && $data['race_specialty'] === 'Everything') {
        $data['race_specialty'] = 'Sprint';
    }

    $request->user()->update($data);

    return Redirect::route('dashboard')->with('status', 'stats-updated');
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
}