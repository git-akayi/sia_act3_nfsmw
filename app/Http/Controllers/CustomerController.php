<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a live leaderboard ranking of all registered drivers.
     */
    public function index()
    {
        // Fetch all registered drivers from the users table, highest bounty at the top
        $rivals = User::orderBy('bounty', 'desc')->get();

        // Pass the live collection array over to your index view file
        return view('customers.index', compact('rivals'));
    }
}