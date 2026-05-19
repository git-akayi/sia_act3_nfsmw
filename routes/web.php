<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController; // 1. IMPORT YOUR CONTROLLER
use Illuminate\Support\Facades\Route;

// The default Welcome page
Route::get('/', function () {
    return view('welcome');
});

// The Dashboard (Updated to fetch dynamic data from the database)
Route::get('/dashboard', function () {
    return view('dashboard');
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
});

// 3. AUTH ROUTES (Leave this at the bottom)
// This pulls in login, register, logout, etc. from the auth.php file
require __DIR__ . '/auth.php';