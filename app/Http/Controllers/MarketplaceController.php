<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\GarageCar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketplaceController extends Controller
{
    /**
     * Display a listing of the vehicles available in the Impound Lot.
     */
    public function index()
    {
        // Fetch all seeded base cars from the master catalog table
        $availabeCars = Car::all();

        return view('marketplace.index', compact('availabeCars'));
    }

    /**
     * Handle the acquisition of a vehicle from the Impound Lot.
     */
    public function store(Request $request)
    {
        // Validate that the requested vehicle actually exists in the master list
        $request->validate([
            'car_id' => 'required|exists:master_cars,id'
        ]);

        $user = Auth::user();
        $baseCar = Car::findOrFail($request->car_id);

        // 1. WALLET SAFETY GUARD: Deny exploit entry using your proper 'cash' field database property
        if ($user->cash < $baseCar->base_market_value) {
            return redirect()->back()->with('error', "TRANSACTION DENIED: Insufficient funds. You need $" . number_format($baseCar->base_market_value - $user->cash) . " more cash.");
        }

        /* |--------------------------------------------------------------------------
        | PERFORMANCE DEGRADATION ENGINE
        |--------------------------------------------------------------------------
        */
        $efficiencyMin = 65;
        $efficiencyMax = 95;
        $wearCondition = rand($efficiencyMin, $efficiencyMax);
        
        $efficiencyMultiplier = $wearCondition / 100;
        $degradedHp = round($baseCar->base_hp * $efficiencyMultiplier);
        $degradedTorque = round($baseCar->base_torque * $efficiencyMultiplier);
        
        // Market valuation depreciates linearly based on the current mechanical wear
        $depreciatedValuation = round($baseCar->base_market_value * $efficiencyMultiplier);

        // 2. Transmit record data directly into the user's garage collection
        GarageCar::create([
            'user_id' => $user->id,
            'car_id' => $baseCar->id,
            'current_hp' => $degradedHp,
            'current_torque' => $degradedTorque,
            'calculated_valuation' => $depreciatedValuation,
            'mechanical_efficiency' => $wearCondition,
        ]);

        // 3. CASH DEDUCTION SYSTEM: Subtract the asset value directly from 'cash' column
        $user->decrement('cash', $baseCar->base_market_value);

        // 4. Re-route the user back to the driver command console with a clean status message
        return redirect()->route('dashboard')->with('success', "// {$baseCar->make_model} ACQUIRED. $" . number_format($baseCar->base_market_value) . " DEDUCTED FROM CASH BALANCE.");
    }
}