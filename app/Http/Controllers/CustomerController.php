<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the Blacklist Rivals.
     */
    public function index()
    {
        // Fetch all rivals sorted by their Blacklist Rank (1 to 15)
        $customers = Customer::orderBy('blacklist_rank', 'asc')->get();

        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new Blacklist Rival.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created Rival in the Rockport PD Database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'alias'          => 'required|string|max:255',
            'car'            => 'required|string|max:255',
            'strength'       => 'required|string|max:255',
            'territory'      => 'required|string|max:255',
            'blacklist_rank' => 'required|integer|between:1,999',
            'bounty'         => 'required|integer|min:0',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Target registered. Rival has been added to the Blacklist.');
    }

    /**
     * Show the form for editing an existing Rival's criminal file.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the Rival's information in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'alias'          => 'required|string|max:255',
            'car'            => 'required|string|max:255',
            'strength'       => 'required|string|max:255',
            'territory'      => 'required|string|max:255',
            'blacklist_rank' => 'required|integer|between:1,999',
            'bounty'         => 'required|integer|min:0',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Criminal file updated. New intel has been saved.');
    }

    /**
     * Remove the Rival from the Blacklist (Take down).
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Rival apprehended. Target removed from active pursuit.');
    }
}