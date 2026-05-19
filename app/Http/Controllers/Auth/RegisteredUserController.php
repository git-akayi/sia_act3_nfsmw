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
     *
     * @throws \Illuminate\Validation\ValidationException
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

        // Complete Official Blacklist 15 Career Milestones
        $blacklistStats = [
            'razor.png'   => ['rank' => 1,  'bounty' => 10000000, 'cars' => 5,  'rivals' => 0],
            'bull.png'    => ['rank' => 2,  'bounty' => 7550000,  'cars' => 4,  'rivals' => 1],
            'ronnie.png'  => ['rank' => 3,  'bounty' => 5550000,  'cars' => 4,  'rivals' => 2],
            'baron.png'   => ['rank' => 4,  'bounty' => 4050000,  'cars' => 3,  'rivals' => 3],
            'webster.png' => ['rank' => 5,  'bounty' => 3050000,  'cars' => 3,  'rivals' => 4],
            'ming.png'    => ['rank' => 6,  'bounty' => 2300000,  'cars' => 2,  'rivals' => 5],
            'kaze.png'    => ['rank' => 7,  'bounty' => 1680000,  'cars' => 2,  'rivals' => 6],
            'jewels.png'  => ['rank' => 8,  'bounty' => 1180000,  'cars' => 2,  'rivals' => 7],
            'earl.png'    => ['rank' => 9,  'bounty' => 790000,   'cars' => 2,  'rivals' => 8],
            'vic.png'     => ['rank' => 10, 'bounty' => 505000,   'cars' => 1,  'rivals' => 9],
            'jv.png'      => ['rank' => 11, 'bounty' => 325000,   'cars' => 1,  'rivals' => 10],
            'izzy.png'    => ['rank' => 12, 'bounty' => 180000,   'cars' => 1,  'rivals' => 11],
            'biglou.png'  => ['rank' => 13, 'bounty' => 85000,    'cars' => 1,  'rivals' => 12],
            'taz.png'     => ['rank' => 14, 'bounty' => 50000,    'cars' => 1,  'rivals' => 13],
            'sonny.png'   => ['rank' => 15, 'bounty' => 20000,    'cars' => 1,  'rivals' => 14],
        ];

        // Evaluate character selection context or drop back to default
        $avatarChosen = $request->avatar ?? 'nfsmw.jpg';
        $stats = $blacklistStats[$avatarChosen] ?? ['rank' => 15, 'bounty' => 0, 'cars' => 0, 'rivals' => 15];

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => $avatarChosen,
            'blacklist_rank' => $stats['rank'],
            'bounty' => $stats['bounty'],
            'cars_owned' => $stats['cars'],
            'rivals_left' => $stats['rivals'],
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}