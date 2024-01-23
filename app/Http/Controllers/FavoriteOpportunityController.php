<?php
// FavoriteOpportunityController.php

namespace App\Http\Controllers;

use App\Models\FavoriteOpportunity;
use Illuminate\Http\Request;

class FavoriteOpportunityController extends Controller
{
    public function index()
    {
        $favoriteOpportunities = FavoriteOpportunity::all();
        return view('favorite_opportunities.index', compact('favoriteOpportunities'));
    }

    public function create()
    {
        return view('favorite_opportunities.create');
    }

    public function store(Request $request)
    {
        // Add validation as per your requirements

        FavoriteOpportunity::create($request->all());

        return redirect()->route('favorite_opportunities.index')->with('success', 'Favorite Opportunity created successfully');
    }

    public function show(FavoriteOpportunity $favoriteOpportunity)
    {
        return view('favorite_opportunities.show', compact('favoriteOpportunity'));
    }

    public function edit(FavoriteOpportunity $favoriteOpportunity)
    {
        return view('favorite_opportunities.edit', compact('favoriteOpportunity'));
    }

    public function update(Request $request, FavoriteOpportunity $favoriteOpportunity)
    {
        // Add validation as per your requirements

        $favoriteOpportunity->update($request->all());

        return redirect()->route('favorite_opportunities.index')->with('success', 'Favorite Opportunity updated successfully');
    }

    public function destroy(FavoriteOpportunity $favoriteOpportunity)
    {
        $favoriteOpportunity->delete();

        return redirect()->route('favorite_opportunities.index')->with('success', 'Favorite Opportunity deleted successfully');
    }
}
