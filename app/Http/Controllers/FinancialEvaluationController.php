<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FinancialEvaluationController extends Controller
{
    public function index() {
        $selected_navigation = 'f e';
        return view('financial-evaluations.index' , ["selected_navigation" => $selected_navigation]);
    }
}
