<?php

namespace App\Http\Controllers;

use App\Models\GarageCar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TuningController extends Controller
{
    public function show($id)
    {
        $user = Auth::user();
        $car = GarageCar::where('id', $id)->where('user_id', $user->id)->with('baseCar')->firstOrFail();
        return view('tuning.show', compact('car'));
    }

    public function tune(Request $request, $id)
    {
        $user = Auth::user();
        $car = GarageCar::where('id', $id)->where('user_id', $user->id)->with('baseCar')->firstOrFail();
        $restoreCost = 1500;

        if ($user->cash < $restoreCost) {
            return redirect()->back()->with('error', "INSUFFICIENT FUNDS.");
        }

        $newEfficiency = min(100, $car->mechanical_efficiency + 10);
        $efficiencyMultiplier = $newEfficiency / 100;
        
        $car->update([
            'mechanical_efficiency' => $newEfficiency,
            'current_hp' => round($car->baseCar->base_hp * $efficiencyMultiplier),
            'current_torque' => round($car->baseCar->base_torque * $efficiencyMultiplier),
            'calculated_valuation' => round($car->baseCar->base_market_value * $efficiencyMultiplier)
        ]);

        $user->decrement('cash', $restoreCost);
        return redirect()->route('tuning.show', $car->id)->with('success', "TUNE STAGE EXECUTED.");
    }

    public function sell($id)
    {
        $user = Auth::user();
        $car = GarageCar::where('id', $id)->where('user_id', $user->id)->with('baseCar')->firstOrFail();

        $premium = ($car->mechanical_efficiency >= 95) ? rand(1.2, 1.4) : (($car->mechanical_efficiency >= 85) ? 1.1 : 0.9);
        $finalOffer = round($car->calculated_valuation * $premium);

        $user->increment('cash', $finalOffer);
        $car->delete();

        return redirect()->route('dashboard')->with('success', "FLIPPED FOR $" . number_format($finalOffer));
    }
}