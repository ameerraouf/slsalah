<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FinancialEvaluation;
use Illuminate\Http\Request;

class FinancialEvaluationController extends Controller
{
    public function index()
    {
        $evaluation = FinancialEvaluation::where('workspace_id' , auth()->user()->workspace_id)->first();
        $selected_navigation = 'f e';
        return view('financial-evaluations.index', ["selected_navigation" => $selected_navigation , "evaluation" => $evaluation]);
    }

    public function create(Request $request)
    {
        $request->validate([
            "industry" => 'required',
            "customers_number" => 'required',
            "yearly_income" => 'required',
            "revnue_rate" => 'required',
            "investments" => 'required',
            "experience" => 'required',
            "rivals" => 'required',
            "market" => 'required',
        ]);

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
                "MarTech" => "150.00",
                "FinTech" => "170.00",
                "EdTech" => "160.00",
                "RegTech" => "140.00",
                "AgriTech" => "150.00",
                "InsurTech" => "170.00",
                "PropTech" => "160.00",
                "InfraTech" => "150.00",
                "CleanTech" => "150.00",
                "GovTech" => "130.00",
                "AdTech" => "170.00",
                "HealthTech" => "150.00",
                "LegalTech" => "150.00",
                "CommTech" => "160.00",
                "E-Commerce" => "180.00",
                "DataTech" => "180.00",
                "BI & Analytics" => "160.00",
                "PM Software" => "160.00",
                "CRM Software" => "160.00",
                "ERP Software" => "160.00",
                "HRM Software" => "160.00",
                "LMS Software" => "160.00",
                "General SaaS" => "160.00",
            ],
            "10 - 20%" => [
                "MarTech" => "130.00",
                "FinTech" => "150.00",
                "EdTech" => "140.00",
                "RegTech" => "120.00",
                "AgriTech" => "130.00",
                "InsurTech" => "150.00",
                "PropTech" => "140.00",
                "InfraTech" => "130.00",
                "CleanTech" => "130.00",
                "GovTech" => "110.00",
                "AdTech" => "150.00",
                "HealthTech" => "130.00",
                "LegalTech" => "130.00",
                "CommTech" => "140.00",
                "E-Commerce" => "160.00",
                "DataTech" => "160.00",
                "BI & Analytics" => "140.00",
                "PM Software" => "140.00",
                "CRM Software" => "140.00",
                "ERP Software" => "140.00",
                "HRM Software" => "140.00",
                "LMS Software" => "140.00",
                "General SaaS" => "140.00",
            ],
            "20 - 40%" => [
                "MarTech" => "110.00",
                "FinTech" => "130.00",
                "EdTech" => "120.00",
                "RegTech" => "100.00",
                "AgriTech" => "110.00",
                "InsurTech" => "130.00",
                "PropTech" => "120.00",
                "InfraTech" => "110.00",
                "CleanTech" => "110.00",
                "GovTech" => "90.00",
                "AdTech" => "130.00",
                "HealthTech" => "110.00",
                "LegalTech" => "110.00",
                "CommTech" => "120.00",
                "E-Commerce" => "140.00",
                "DataTech" => "140.00",
                "BI & Analytics" => "120.00",
                "PM Software" => "120.00",
                "CRM Software" => "120.00",
                "ERP Software" => "120.00",
                "HRM Software" => "120.00",
                "LMS Software" => "120.00",
                "General SaaS" => "120.00",
            ],
            "40 - 60%" => [
                "MarTech" => "90.00",
                "FinTech" => "110.00",
                "EdTech" => "100.00",
                "RegTech" => "80.00",
                "AgriTech" => "90.00",
                "InsurTech" => "110.00",
                "PropTech" => "100.00",
                "InfraTech" => "90.00",
                "CleanTech" => "90.00",
                "GovTech" => "70.00",
                "AdTech" => "110.00",
                "HealthTech" => "90.00",
                "LegalTech" => "90.00",
                "CommTech" => "100.00",
                "E-Commerce" => "120.00",
                "DataTech" => "120.00",
                "BI & Analytics" => "100.00",
                "PM Software" => "100.00",
                "CRM Software" => "100.00",
                "ERP Software" => "100.00",
                "HRM Software" => "100.00",
                "LMS Software" => "100.00",
                "General SaaS" => "100.00",
            ],
            "60 - 100%" => [
                "MarTech" => "70.00",
                "FinTech" => "90.00",
                "EdTech" => "80.00",
                "RegTech" => "60.00",
                "AgriTech" => "70.00",
                "InsurTech" => "90.00",
                "PropTech" => "80.00",
                "InfraTech" => "70.00",
                "CleanTech" => "70.00",
                "GovTech" => "50.00",
                "AdTech" => "90.00",
                "HealthTech" => "70.00",
                "LegalTech" => "70.00",
                "CommTech" => "80.00",
                "E-Commerce" => "100.00",
                "DataTech" => "100.00",
                "BI & Analytics" => "80.00",
                "PM Software" => "80.00",
                "CRM Software" => "80.00",
                "ERP Software" => "80.00",
                "HRM Software" => "80.00",
                "LMS Software" => "80.00",
                "General SaaS" => "80.00",
            ],
            "100" => [
                "MarTech" => "50.00",
                "FinTech" => "70.00",
                "EdTech" => "60.00",
                "RegTech" => "40.00",
                "AgriTech" => "50.00",
                "InsurTech" => "70.00",
                "PropTech" => "60.00",
                "InfraTech" => "50.00",
                "CleanTech" => "50.00",
                "GovTech" => "30.00",
                "AdTech" => "70.00",
                "HealthTech" => "50.00",
                "LegalTech" => "50.00",
                "CommTech" => "60.00",
                "E-Commerce" => "80.00",
                "DataTech" => "80.00",
                "BI & Analytics" => "60.00",
                "PM Software" => "60.00",
                "CRM Software" => "60.00",
                "ERP Software" => "60.00",
                "HRM Software" => "60.00",
                "LMS Software" => "60.00",
                "General SaaS" => "60.00",
            ],
        ];
        $rrr = $revenue_rate[$request->revnue_rate][$request->industry];

        // m5 (investments)
        $m5 = 0;
        if ($request->investments == '0') {
            $m5 = 0.25 + $m1;
        } elseif ($request->investments == '0 - 500.000') {
            $m5 = 0.75 + $m1;
        } elseif ($request->investments == '500.000 - 1.000.000') {
            $m5 = 1 + $m1;
        } elseif ($request->investments == '1.000.000 - 10.000.000') {
            $m5 = 1.25 + $m1;
        } elseif ($request->investments == '10.000.000') {
            $m5 = 1.75 + $m1;
        }
        // m6 (experience)
        $m6 = 0;
        if ($request->experience == '0') {
            $m6 = 0.25 + $m1;
        } elseif ($request->experience == '1 - 3') {
            $m6 = 1 + $m1;
        } elseif ($request->experience == '3 - 5') {
            $m6 = 1.25 + $m1;
        } elseif ($request->experience == '5 - 10') {
            $m6 = 1.5 + $m1;
        } elseif ($request->experience == '10') {
            $m6 = 2 + $m1;
        }

        // m6 (rivals)
        $m7 = 0;
        if ($request->rivals == '0') {
            $m7 = 0.25 + $m1;
        } elseif ($request->rivals == '1 -3') {
            $m7 = 1.15 + $m1;
        } elseif ($request->rivals == '3 - 5') {
            $m7 = 1.4 + $m1;
        } elseif ($request->rivals == '5 - 10') {
            $m7 = 1.65 + $m1;
        } elseif ($request->rivals == '10') {
            $m7 = 2.15 + $m1;
        }

        // m6 (market)
        $m8 = 0;
        if ($request->market == '1') {
            $m8 = 0.5 + $m1;
        } elseif ($request->market == '1 - 3') {
            $m8 = 1.4 + $m1;
        } elseif ($request->market == '3 - 5') {
            $m8 = 1.65 + $m1;
        } elseif ($request->market == '5 - 10') {
            $m8 = 1.9 + $m1;
        } elseif ($request->market == '10') {
            $m8 = 2.4 + $m1;
        }
        $r = $request->yearly_income;
        $AVGM = ($m1 * 0.05) + ($m2 * 0.05) + ($m5 * 0.05) + ($m6 * 0.05) + ($m7 * 0.3) * ($m8 * 0.5);
        $result = ($r * $AVGM) / pow((1 + ($rrr / 100)) , 3);

        $evaluation = FinancialEvaluation::where('workspace_id' , auth()->user()->workspace_id)->first();

        if ($evaluation) {
            $evaluation->update(["value" => $result]);
        } else {
            FinancialEvaluation::create([
                'workspace_id' => auth()->user()->workspace_id,
                "value" => $result
            ]);
        }
        return $result;
    }
}
