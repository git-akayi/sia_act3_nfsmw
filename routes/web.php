<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MarketplaceController;
use App\Models\GarageCar; // Imported to fetch the user's custom cars
use Illuminate\Support\Facades\Auth; // Imported to safely get the logged-in player's ID
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TuningController;
use App\Http\Controllers\RaceController;

// The default Welcome page
Route::get('/', function () {
    return view('welcome');
});

// The Dashboard (Updated to fetch dynamic data from the database)
Route::get('/dashboard', function () {
    // Query the logged-in user's garage cars and eager load their baseline catalog specs
    $myGarageCars = GarageCar::where('user_id', Auth::id())
        ->with('baseCar')
        ->get();

    // Pass the collection data directly into your dashboard blade view layout
    return view('dashboard', compact('myGarageCars'));
})->middleware(['auth', 'verified'])->name('dashboard');

// 2. YOUR AUTHENTICATED ROUTES
// We wrap this in 'auth' so only logged-in users can see these sections
Route::middleware('auth')->group(function () {

    // Dynamic Leaderboard Route (Replaces old manual resource controller)
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');

    // Profile routes (Breeze default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Performance Tuner parameter injector route
    Route::patch('/dashboard/tune-stats', [ProfileController::class, 'updateStats'])->name('dashboard.tune-stats');

    // Marketplace Web Routes
    Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
    Route::post('/marketplace', [MarketplaceController::class, 'store'])->name('marketplace.store');

    // Tuning and Customization Workshop
    Route::get('/tuning/{id}', [TuningController::class, 'show'])->name('tuning.show');
    Route::post('/tuning/{id}/upgrade', [TuningController::class, 'tune'])->name('tuning.upgrade');

    Route::post('/tuning/{id}/sell', [TuningController::class, 'sell'])->name('tuning.sell');

    Route::patch('/profile/update-stats', [ProfileController::class, 'updateStats'])->name('profile.updateStats');

    Route::post('/race/blacklist', [RaceController::class, 'blacklistRace'])->name('race.blacklist');

    Route::middleware('auth')->group(function () {
        Route::get('/race', [RaceController::class, 'index'])->name('race.index');
        Route::post('/race', [RaceController::class, 'race'])->name('race.run');
    });
});

// 3. AUTH ROUTES (Leave this at the bottom)
// This pulls in login, register, logout, etc. from the auth.php file
require __DIR__ . '/auth.php';
