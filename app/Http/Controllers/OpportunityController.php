<?php
// OpportunityController.php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use Illuminate\Http\Request;

class OpportunityController extends Controller
{
    public function index()
    {
        $opportunities = Opportunity::all();
        return view('opportunities.index', compact('opportunities'));
    }

    public function create()
    {
        return view('opportunities.create');
    }

    public function store(Request $request)
    {
        // Add validation as per your requirements

        Opportunity::create($request->all());

        return redirect()->route('opportunities.index')->with('success', 'Opportunity created successfully');
    }

    public function show(Opportunity $opportunity)
    {
        return view('opportunities.show', compact('opportunity'));
    }

    public function edit(Opportunity $opportunity)
    {
        return view('opportunities.edit', compact('opportunity'));
    }

    public function update(Request $request, Opportunity $opportunity)
    {
        // Add validation as per your requirements

        $opportunity->update($request->all());

        return redirect()->route('opportunities.index')->with('success', 'Opportunity updated successfully');
    }

    public function destroy(Opportunity $opportunity)
    {
        $opportunity->delete();

        return redirect()->route('opportunities.index')->with('success', 'Opportunity deleted successfully');
    }
}
