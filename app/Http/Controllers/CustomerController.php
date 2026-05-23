<?php

namespace App\Http\Controllers;

use App\Models\User; // Your existing user import
use App\Models\GarageCar; // <--- ADD THIS LINE HERE
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <--- ADD THIS LINE HERE TOO

class CustomerController extends Controller
{
    /**
     * Display a listing of the drivers and the personal garage.
     */
   public function index()
{
    // Rank dynamically by bounty, then assign rank position
    $users = User::orderBy('bounty', 'desc')->get();

    $myGarageCars = GarageCar::where('user_id', Auth::id())
        ->with('baseCar')
        ->get();

    return view('customers.index', compact('users', 'myGarageCars'));
}
}