<?php

namespace App\Http\Controllers;

use App\Models\GarageCar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TuningController extends Controller
{
    // ── Web views ─────────────────────────────────────────────────────────────

    public function show($id)
    {
        $user = Auth::user();
        $car  = GarageCar::where('id', $id)
                    ->where('user_id', $user->id)
                    ->with('baseCar')
                    ->firstOrFail();

        return view('tuning.show', compact('car'));
    }

    public function tune(Request $request, $id)
    {
        $user        = Auth::user();
        $car         = GarageCar::where('id', $id)
                            ->where('user_id', $user->id)
                            ->with('baseCar')
                            ->firstOrFail();
        $restoreCost = 1500;

        if ($user->cash < $restoreCost) {
            return redirect()->back()->with('error', 'INSUFFICIENT FUNDS.');
        }

        $newEfficiency        = min(100, $car->mechanical_efficiency + 10);
        $efficiencyMultiplier = $newEfficiency / 100;

        $car->update([
            'mechanical_efficiency' => $newEfficiency,
            'current_hp'            => round($car->baseCar->base_hp * $efficiencyMultiplier),
            'current_torque'        => round($car->baseCar->base_torque * $efficiencyMultiplier),
            'calculated_valuation'  => round($car->baseCar->base_market_value * $efficiencyMultiplier),
        ]);

        $user->decrement('cash', $restoreCost);

        return redirect()->route('tuning.show', $car->id)->with('success', 'TUNE STAGE EXECUTED.');
    }

    public function sell($id)
    {
        $user    = Auth::user();
        $car     = GarageCar::where('id', $id)
                        ->where('user_id', $user->id)
                        ->with('baseCar')
                        ->firstOrFail();

        $premium    = ($car->mechanical_efficiency >= 95)
                        ? rand(12, 14) / 10
                        : (($car->mechanical_efficiency >= 85) ? 1.1 : 0.9);
        $finalOffer = round($car->calculated_valuation * $premium);

        $user->increment('cash', $finalOffer);
        $car->delete();

        return redirect()->route('dashboard')->with('success', 'FLIPPED FOR $' . number_format($finalOffer));
    }

    // ── API (Flutter) ─────────────────────────────────────────────────────────

    public function apiTune(Request $request, $id)
    {
        $user = $request->user();
        $car  = GarageCar::where('id', $id)
                    ->where('user_id', $user->id)
                    ->with('baseCar')
                    ->firstOrFail();

        if ($car->mechanical_efficiency >= 100) {
            return response()->json([
                'success' => false,
                'message' => 'ECU ALREADY AT PEAK EFFICIENCY.',
            ]);
        }

        $restoreCost = 1500;

        if ($user->cash < $restoreCost) {
            return response()->json([
                'success' => false,
                'message' => 'INSUFFICIENT FUNDS. NEED $' . number_format($restoreCost) . ' TO TUNE.',
            ]);
        }

        $newEfficiency        = min(100, $car->mechanical_efficiency + 10);
        $efficiencyMultiplier = $newEfficiency / 100;

        $car->update([
            'mechanical_efficiency' => $newEfficiency,
            'current_hp'            => round($car->baseCar->base_hp * $efficiencyMultiplier),
            'current_torque'        => round($car->baseCar->base_torque * $efficiencyMultiplier),
            'calculated_valuation'  => round($car->baseCar->base_market_value * $efficiencyMultiplier),
        ]);

        $user->decrement('cash', $restoreCost);

        return response()->json([
            'success' => true,
            'message' => 'ECU CALIBRATED. +10% EFFICIENCY. $1,500 DEDUCTED.',
            'car'     => [
                'id'                    => $car->id,
                'make_model'            => $car->baseCar->make_model,
                'current_hp'            => $car->current_hp,
                'current_torque'        => $car->current_torque,
                'mechanical_efficiency' => $car->mechanical_efficiency,
                'calculated_valuation'  => $car->calculated_valuation,
                'engine_type'           => $car->baseCar->engine_type,
                'top_speed'             => $car->baseCar->top_speed,
                'weight_kg'             => $car->baseCar->weight_kg,
                'transmission'          => $car->baseCar->transmission,
                'base_hp'               => $car->baseCar->base_hp,
                'base_torque'           => $car->baseCar->base_torque,
            ],
        ]);
    }

    public function apiSell(Request $request, $id)
    {
        $user = $request->user();
        $car  = GarageCar::where('id', $id)
                    ->where('user_id', $user->id)
                    ->with('baseCar')
                    ->firstOrFail();

        $premium    = ($car->mechanical_efficiency >= 95)
                        ? rand(12, 14) / 10
                        : (($car->mechanical_efficiency >= 85) ? 1.1 : 0.9);
        $finalOffer = round($car->calculated_valuation * $premium);
        $makeModel  = $car->baseCar->make_model;

        $user->increment('cash', $finalOffer);
        $car->delete();

        return response()->json([
            'success' => true,
            'message' => "$makeModel FLIPPED FOR \$" . number_format($finalOffer) . '. CASH ADDED.',
        ]);
    }
    // Add this inside TuningController
public function apiGarage(Request $request)
{
    $cars = GarageCar::where('user_id', $request->user()->id)
        ->with('baseCar')
        ->get()
        ->map(function ($car) {
            return [
                'id'                    => $car->id,
                'make_model'            => $car->baseCar->make_model,
                'current_hp'            => $car->current_hp,
                'current_torque'        => $car->current_torque,
                'mechanical_efficiency' => $car->mechanical_efficiency,
                'calculated_valuation'  => $car->calculated_valuation,
                'engine_type'           => $car->baseCar->engine_type,
                'top_speed'             => $car->baseCar->top_speed,
                'weight_kg'             => $car->baseCar->weight_kg,
                'transmission'          => $car->baseCar->transmission,
                'base_hp'               => $car->baseCar->base_hp,
                'base_torque'           => $car->baseCar->base_torque,
                'tier'                  => $car->baseCar->tier,
            ];
        });

    return response()->json($cars);
}
}