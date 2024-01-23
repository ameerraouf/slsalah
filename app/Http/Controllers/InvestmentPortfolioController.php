<?php
// InvestmentPortfolioController.php

namespace App\Http\Controllers;

use App\Models\InvestmentPortfolio;
use Illuminate\Http\Request;

class InvestmentPortfolioController extends Controller
{
    public function index()
    {
        $investmentPortfolios = InvestmentPortfolio::all();
        return view('investment_portfolios.index', compact('investmentPortfolios'));
    }

    public function create()
    {
        return view('investment_portfolios.create');
    }

    public function store(Request $request)
    {
        // Add validation as per your requirements

        InvestmentPortfolio::create($request->all());

        return redirect()->route('investment_portfolios.index')->with('success', 'Investment Portfolio created successfully');
    }

    public function show(InvestmentPortfolio $investmentPortfolio)
    {
        return view('investment_portfolios.show', compact('investmentPortfolio'));
    }

    public function edit(InvestmentPortfolio $investmentPortfolio)
    {
        return view('investment_portfolios.edit', compact('investmentPortfolio'));
    }

    public function update(Request $request, InvestmentPortfolio $investmentPortfolio)
    {
        // Add validation as per your requirements

        $investmentPortfolio->update($request->all());

        return redirect()->route('investment_portfolios.index')->with('success', 'Investment Portfolio updated successfully');
    }

    public function destroy(InvestmentPortfolio $investmentPortfolio)
    {
        $investmentPortfolio->delete();

        return redirect()->route('investment_portfolios.index')->with('success', 'Investment Portfolio deleted successfully');
    }
}
