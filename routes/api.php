<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LeaderboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RaceController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\TuningController;

// Public
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/leaderboard',      [LeaderboardController::class, 'index']);
    Route::get('/leaderboard/{id}', [LeaderboardController::class, 'show']);

    Route::get('/my-profile', [LeaderboardController::class, 'myProfile']);
    Route::put('/my-profile', [ProfileController::class, 'apiUpdateProfile']);

    Route::get('/dashboard', [ProfileController::class, 'apiDashboard']);

    Route::post('/race',        [RaceController::class, 'apiRace']);
    Route::get('/race/history', [RaceController::class, 'apiHistory']);

    Route::get('/marketplace',           [MarketplaceController::class, 'index']);
    Route::post('/marketplace/buy/{id}', [MarketplaceController::class, 'buy']);

    Route::post('/garage/{id}/tune', [TuningController::class, 'apiTune']);
    Route::delete('/garage/{id}/sell', [TuningController::class, 'apiSell']);
    Route::get('/my-garage', [TuningController::class, 'apiGarage']);

    Route::post('/race/blacklist', [RaceController::class, 'apiBlacklistRace']);
    Route::get('/race/blacklist/status', [RaceController::class, 'apiBlacklistStatus']);
});
