<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                \Illuminate\Validation\Rule::unique('users', 'email')
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'avatar' => ['nullable', 'string'],
        ]);

        // Career Milestones
        $blacklistStats = [
            'razor.png'   => ['rank' => 1,  'cars' => 5, 'rivals' => 0],
            'bull.png'    => ['rank' => 2,  'cars' => 4, 'rivals' => 1],
            'ronnie.png'  => ['rank' => 3,  'cars' => 4, 'rivals' => 2],
            'baron.png'   => ['rank' => 4,  'cars' => 3, 'rivals' => 3],
            'webster.png' => ['rank' => 5,  'cars' => 3, 'rivals' => 4],
            'ming.png'    => ['rank' => 6,  'cars' => 2, 'rivals' => 5],
            'kaze.png'    => ['rank' => 7,  'cars' => 2, 'rivals' => 6],
            'jewels.png'  => ['rank' => 8,  'cars' => 2, 'rivals' => 7],
            'earl.png'    => ['rank' => 9,  'cars' => 2, 'rivals' => 8],
            'vic.png'     => ['rank' => 10, 'cars' => 1, 'rivals' => 9],
            'jv.png'      => ['rank' => 11, 'cars' => 1, 'rivals' => 10],
            'izzy.png'    => ['rank' => 12, 'cars' => 1, 'rivals' => 11],
            'biglou.png'  => ['rank' => 13, 'cars' => 1, 'rivals' => 12],
            'taz.png'     => ['rank' => 14, 'cars' => 1, 'rivals' => 13],
            'sonny.png'   => ['rank' => 15, 'cars' => 1, 'rivals' => 14],
        ];

        // Evaluate character selection context or drop back to default
        $avatarChosen = $request->avatar ?? 'nfsmw.jpg';
        $stats = $blacklistStats[$avatarChosen] ?? ['rank' => 15, 'cars' => 0, 'rivals' => 15];

        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'avatar'         => $avatarChosen,
            'blacklist_rank' => 15, // Everyone starts at the bottom
            'bounty'         => 0,
            'cars_owned'     => 0,
            'rivals_left'    => 15,
            'cash'           => 15000,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
