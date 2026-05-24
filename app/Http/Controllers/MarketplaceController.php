<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\GarageCar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketplaceController extends Controller
{
    /**
     * Display all vehicles available in the Impound Lot.
     * Returns JSON for API requests, Blade view for web requests.
     */
    public function index(Request $request)
    {
        $availabeCars = Car::all();

        if ($request->expectsJson()) {
            return response()->json($availabeCars);
        }

        return view('marketplace.index', compact('availabeCars'));
    }

    /**
     * Handle vehicle acquisition from the Impound Lot (web form POST).
     */
    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:master_cars,id'
        ]);

        $user    = Auth::user();
        $baseCar = Car::findOrFail($request->car_id);

        if ($user->cash < $baseCar->base_market_value) {
            return redirect()->back()->with(
                'error',
                "TRANSACTION DENIED: Insufficient funds. You need $"
                . number_format($baseCar->base_market_value - $user->cash)
                . " more cash."
            );
        }

        $wearCondition        = rand(65, 95);
        $efficiencyMultiplier = $wearCondition / 100;

        GarageCar::create([
            'user_id'               => $user->id,
            'car_id'                => $baseCar->id,
            'current_hp'            => round($baseCar->base_hp * $efficiencyMultiplier),
            'current_torque'        => round($baseCar->base_torque * $efficiencyMultiplier),
            'calculated_valuation'  => round($baseCar->base_market_value * $efficiencyMultiplier),
            'mechanical_efficiency' => $wearCondition,
        ]);

        $user->decrement('cash', $baseCar->base_market_value);

        return redirect()->route('dashboard')->with(
            'success',
            "// {$baseCar->make_model} ACQUIRED. $"
            . number_format($baseCar->base_market_value)
            . " DEDUCTED FROM CASH BALANCE."
        );
    }

    /**
     * Handle vehicle purchase via API (JSON).
     */
    public function buy(Request $request, $id)
    {
        $user    = $request->user();
        $baseCar = Car::findOrFail($id);

        if ($user->cash < $baseCar->base_market_value) {
            return response()->json([
                'success' => false,
                'message' => 'INSUFFICIENT FUNDS. Need $'
                    . number_format($baseCar->base_market_value - $user->cash)
                    . ' more.',
            ]);
        }

        $wearCondition        = rand(65, 95);
        $efficiencyMultiplier = $wearCondition / 100;

        GarageCar::create([
            'user_id'               => $user->id,
            'car_id'                => $baseCar->id,
            'current_hp'            => round($baseCar->base_hp * $efficiencyMultiplier),
            'current_torque'        => round($baseCar->base_torque * $efficiencyMultiplier),
            'calculated_valuation'  => round($baseCar->base_market_value * $efficiencyMultiplier),
            'mechanical_efficiency' => $wearCondition,
        ]);

        $user->decrement('cash', $baseCar->base_market_value);

        return response()->json([
            'success' => true,
            'message' => "{$baseCar->make_model} ACQUIRED. $"
                . number_format($baseCar->base_market_value)
                . " DEDUCTED.",
        ]);
    }
}