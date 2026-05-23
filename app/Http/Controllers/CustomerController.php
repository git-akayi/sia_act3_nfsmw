<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\User; // Ensure the User model is imported at the top
=======
use App\Models\User;
>>>>>>> 9eb49a5d31a45d4102f15ba687f8b48625505da8
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
<<<<<<< HEAD
     * Display a listing of the drivers.
     */
    public function index()
    {
        // 1. Fetch your user table records ordered by their blacklist ranking hierarchy
        $users = User::orderBy('blacklist_rank', 'asc')->get();

        // 2. Return the customers index view while feeding it our dynamic collection array
        return view('customers.index', compact('users'));
=======
     * Display a live leaderboard ranking of all registered drivers.
     */
    public function index()
    {
        // Fetch all registered drivers from the users table, highest bounty at the top
        $rivals = User::orderBy('bounty', 'desc')->get();

        // Pass the live collection array over to your index view file
        return view('customers.index', compact('rivals'));
>>>>>>> 9eb49a5d31a45d4102f15ba687f8b48625505da8
    }
}