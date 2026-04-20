<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController; // 1. IMPORT YOUR CONTROLLER
use Illuminate\Support\Facades\Route;

// The default Welcome page
Route::get('/', function () {
    return view('welcome');
});

// The Dashboard (Breeze default)
Route::get('/dashboard', function () {
    $stats = [
        'blacklist_rank' => '1',
        'blacklist_left' => '0',
        'bounty' => '$ 25,123,265',
        'cars_owned' => '36',
        'rap_sheet_rank' => '1',
    ];
    return view('dashboard', compact('stats'));
})->middleware(['auth', 'verified'])->name('dashboard');

// 2. YOUR CUSTOMER ROUTES
// We wrap this in 'auth' so only logged-in users can see the customers
Route::middleware('auth')->group(function () {

    // This single line handles index, create, store, edit, update, and destroy
    Route::resource('customers', CustomerController::class);

    // Profile routes (Breeze default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 3. AUTH ROUTES (Leave this at the bottom)
// This pulls in login, register, logout, etc. from the auth.php file
require __DIR__ . '/auth.php';