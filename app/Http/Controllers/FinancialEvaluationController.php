<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FinancialEvaluationController extends Controller
{
    public function index()
    {
        $selected_navigation = 'f e';
        return view('financial-evaluations.index', ["selected_navigation" => $selected_navigation]);
    }

    public function create(Request $request)
    {
        $industries = [
            "MarTech" => 2.4,
            "FinTech" => 6.4,
            "EdTech" => 3,
            "RegTech" => 3.3,
            "AgriTech" => 0.2,
            "InsurTech" => 1,
            "PropTech" => 4,
            "InfraTech" => 1.7,
            "CleanTech" => 1.1,
            "GovTech" => -0.2,
            "AdTech" => 0.3,
            "HealthTech" => 3.6,
            "LegalTech" => 2.8,
            "CommTech" => 2.1,
            "E-Commerce" => 1.2,
            "DataTech" => 2,
            "BI & Analytics" => 2.6,
            "PM Software" => 1.6,
            "CRM Software" => 1.7,
            "ERP Software" => 3.8,
            "HRM Software" => 3.7,
            "LMS Software" => 3.3,
            "General SaaS" => 2.6,
        ];
        // m1
        $m1 = $industries[$request->industry];
        // m2 
        $customers = [
            "0-1000" => 0.25,
            "1000 - 10000" => 0.5,
            "10000" => 1
        ];
        $m2 = $m1 + $customers[$request->customers_number];



        // rrr 
        $revenue_rate = [
            "0 - 10%" => [
                "MarTech" => "150.00%",
                "FinTech" => "170.00%",
                "EdTech" => "160.00%",
                "RegTech" => "140.00%",
                "AgriTech" => "150.00%",
                "InsurTech" => "170.00%",
                "PropTech" => "160.00%",
                "InfraTech" => "150.00%",
                "CleanTech" => "150.00%",
                "GovTech" => "130.00%",
                "AdTech" => "170.00%",
                "HealthTech" => "150.00%",
                "LegalTech" => "150.00%",
                "CommTech" => "160.00%",
                "E-Commerce" => "180.00%",
                "DataTech" => "180.00%",
                "BI & Analytics" => "160.00%",
                "PM Software" => "160.00%",
                "CRM Software" => "160.00%",
                "ERP Software" => "160.00%",
                "HRM Software" => "160.00%",
                "LMS Software" => "160.00%",
                "General SaaS" => "160.00%",
            ],
            "10 - 20%" => [
                "MarTech" => "130.00%",
                "FinTech" => "150.00%",
                "EdTech" => "140.00%",
                "RegTech" => "120.00%",
                "AgriTech" => "130.00%",
                "InsurTech" => "150.00%",
                "PropTech" => "140.00%",
                "InfraTech" => "130.00%",
                "CleanTech" => "130.00%",
                "GovTech" => "110.00%",
                "AdTech" => "150.00%",
                "HealthTech" => "130.00%",
                "LegalTech" => "130.00%",
                "CommTech" => "140.00%",
                "E-Commerce" => "160.00%",
                "DataTech" => "160.00%",
                "BI & Analytics" => "140.00%",
                "PM Software" => "140.00%",
                "CRM Software" => "140.00%",
                "ERP Software" => "140.00%",
                "HRM Software" => "140.00%",
                "LMS Software" => "140.00%",
                "General SaaS" => "140.00%",
            ],
            "20 - 40%" => [],
            "40 - 60%" => [],
            "60 - 100%" => [],
            "100" => [],
        ];

        $m3 = $revenue_rate[$request->revnue_rate][$request->industry];
        return $request->all();
    }
}
