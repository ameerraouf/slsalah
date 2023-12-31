<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\FinancialProjections\PlanningRevenueOperatingAssumptionsRequest;
use App\Models\FixedInvestedCapital;
use App\Models\PlanningCostAssumption;
use App\Models\PlanningFinancialAssumption;
use App\Models\PlanningRevenueOperatingAssumption;
use App\Models\ProjectRevenuePlanning;
use App\Models\WorkingInvestedCapital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanningRevenueOperatingAssumptionController extends BaseController
{

    public function index(){
        if ($this->modules && !in_array("planning_cost_assumptions", $this->modules)) {
            abort(403);
        }
        $planningRevenueOperatingAssumption = PlanningRevenueOperatingAssumption::where(['workspace_id' =>$this->user->workspace_id])->first();

        return \view("planning_revenue_operating_assumptions.index", [
            "selected_navigation" => "planning_revenue_operating_assumptions",
            "planningRevenueOperatingAssumption" => $planningRevenueOperatingAssumption,
        ]);
    }
    public function store(PlanningRevenueOperatingAssumptionsRequest $request){
        if(!empty($this->user->workspace_id)) {
            $planningRevenueOperatingAssumptionExistence = PlanningRevenueOperatingAssumption::where('workspace_id', $this->user->workspace_id)
                ->first();
            if ($planningRevenueOperatingAssumptionExistence) {
                $planningRevenueOperatingAssumptionExistence->update([
                    'first_year' => $request->first_year,
                    'second_year' => $request->second_year,
                    'third_year' => $request->third_year
                ]);
            } else {
                PlanningRevenueOperatingAssumption::create([
                    'first_year' => $request->first_year,
                    'second_year' => $request->second_year,
                    'third_year' => $request->third_year,
                    'workspace_id' => $this->user->workspace_id
                ]);
            }
            return response()->json([
                'msg' => __('planningRevenueOperatingAssumptionsStored'),
                'type' => 'success',
//                'revenueForecastsAfterOperatingAssumptions'=>$this->revenueForecastsAfterOperatingAssumptions(),
//                'getIncomeList'=>$this->getIncomeList(),
//                'getStatementOfCashFlows'=>$this->getStatementOfCashFlows(),
//                'capital_investment_model'=>$this->capital_investment_model(),
            ]);
        }else{
            return response()->json(['msg' => __('noUserWorkspaceFound'), 'type' => 'error']);
        }
    }
    public function getPlanningRevenueOperatingAssumptions(Request $request){
        return response()->json($this->revenueForecastsAfterOperatingAssumptions());
    }
    public function revenueForecastsAfterOperatingAssumptions(){
        $planningRevenueOperatingAssumptions = PlanningRevenueOperatingAssumption::where('workspace_id', $this->user->workspace_id)
            ->first();
        $projectRevenuesPlanning = ProjectRevenuePlanning::with(['sources'])->where(['workspace_id' =>$this->user->workspace_id])->get();
        return view('planning_revenue_operating_assumptions.revenue_forecasts_after_operating_assumptions',
            compact('planningRevenueOperatingAssumptions','projectRevenuesPlanning'))->render();
    }
    public function getIncomeList(){
        $planningRevenueOperatingAssumptions = PlanningRevenueOperatingAssumption::where('workspace_id', $this->user->workspace_id)
            ->first();
        $all_revenues_forecasting = $planningRevenueOperatingAssumptions ? $planningRevenueOperatingAssumptions->all_revenues_forecasting : ['first_year'=>0,'second_year'=>0,'third_year'=>0];
        $all_revenues_costs_forecasting = $planningRevenueOperatingAssumptions ? $planningRevenueOperatingAssumptions->all_revenues_costs_forecasting : ['first_year'=>0,'second_year'=>0,'third_year'=>0];
        $planningCostAssumption = PlanningCostAssumption::where(['workspace_id' =>$this->user->workspace_id])
            ->first();
        $planningFinancialAssumption = PlanningFinancialAssumption::where('workspace_id', $this->user->workspace_id)
            ->first();
        $calc_total = $planningRevenueOperatingAssumptions->calc_total;
//        dd($planningRevenueOperatingAssumptions->all_revenues_forecasting);
        return view('planning_revenue_operating_assumptions.getIncomeList',
            compact('calc_total','planningRevenueOperatingAssumptions','planningFinancialAssumption','planningCostAssumption','all_revenues_forecasting','all_revenues_costs_forecasting'))->render();
    }
    public function getStatementOfCashFlows(){
        $planningRevenueOperatingAssumptions = PlanningRevenueOperatingAssumption::where('workspace_id', $this->user->workspace_id)
            ->first();
        $all_revenues_forecasting = $planningRevenueOperatingAssumptions ? $planningRevenueOperatingAssumptions->all_revenues_forecasting : ['first_year'=>0,'second_year'=>0,'third_year'=>0];
        $all_revenues_costs_forecasting = $planningRevenueOperatingAssumptions ? $planningRevenueOperatingAssumptions->all_revenues_costs_forecasting : ['first_year'=>0,'second_year'=>0,'third_year'=>0];
        $planningCostAssumption = PlanningCostAssumption::where(['workspace_id' =>$this->user->workspace_id])
            ->first();
        $planningFinancialAssumption = PlanningFinancialAssumption::where('workspace_id', $this->user->workspace_id)
            ->first();

//        dd($planningRevenueOperatingAssumptions->all_revenues_forecasting);
        return view('planning_revenue_operating_assumptions.statement_of_cash_flows',
            compact('planningRevenueOperatingAssumptions','planningFinancialAssumption','planningCostAssumption','all_revenues_forecasting','all_revenues_costs_forecasting'))->render();
    }
    public function capital_investment_model(){
        $planningRevenueOperatingAssumptions = PlanningRevenueOperatingAssumption::where('workspace_id', $this->user->workspace_id)
            ->first();
        $planningFinancialAssumption = PlanningFinancialAssumption::where('workspace_id', $this->user->workspace_id)
            ->first();

        $all_revenues_forecasting = $planningRevenueOperatingAssumptions ? $planningRevenueOperatingAssumptions->all_revenues_forecasting : ['first_year'=>0,'second_year'=>0,'third_year'=>0];
        $all_revenues_costs_forecasting = $planningRevenueOperatingAssumptions ? $planningRevenueOperatingAssumptions->all_revenues_costs_forecasting : ['first_year'=>0,'second_year'=>0,'third_year'=>0];

        $workingInvestedTotal = WorkingInvestedCapital::select(DB::raw('SUM(investing_annual_cost) as investing_annual_cost_total'))->where("workspace_id", $this->user->workspace_id)->get()->pluck('investing_annual_cost_total');
        $fixedInvestedTotal = FixedInvestedCapital::select(DB::raw('SUM(investing_price) as investing_price_total'))->where("workspace_id", $this->user->workspace_id)->get()->pluck('investing_price_total');
//        dd();
        $totalInvestedCapital = (!empty($workingInvestedTotal) ? $workingInvestedTotal[0] : 0.0)+(!empty($fixedInvestedTotal) ? $fixedInvestedTotal[0] : 0.0);


//        dd($all_revenues_forecasting,$all_revenues_costs_forecasting);
        return view('planning_revenue_operating_assumptions.capital_investment_model',
            compact('planningRevenueOperatingAssumptions','planningFinancialAssumption','all_revenues_forecasting','all_revenues_costs_forecasting','totalInvestedCapital'))->render();
    }
}
