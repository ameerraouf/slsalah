<?php

namespace App\Http\Controllers;

use App\Models\FixedInvestedCapital;
use App\Models\PlanningCostAssumption;
use App\Models\PlanningFinancialAssumption;
use App\Models\PlanningRevenueOperatingAssumption;
use App\Models\ProjectRevenuePlanning;
use App\Models\WorkingInvestedCapital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinncialReportController extends BaseController
{
    //

    public function getPlanningRevenueOperatingAssumptions(Request $request){
        $planningRevenueOperatingAssumptions = PlanningRevenueOperatingAssumption::where('workspace_id', $this->user->workspace_id)
            ->first();
        $projectRevenuesPlanning = ProjectRevenuePlanning::with(['sources'])->where(['workspace_id' =>$this->user->workspace_id])->get();
        $selected_navigation = 'PlanningRevenueOperatingAssumptions';

        return view('planning_revenue_operating_assumptions.revenue_forecasts_after_operating_assumptions',
            compact('planningRevenueOperatingAssumptions','projectRevenuesPlanning', 'selected_navigation'));

    }

    public function getIncomeList(Request $request){
        $planningRevenueOperatingAssumptions = PlanningRevenueOperatingAssumption::where('workspace_id', $this->user->workspace_id)
            ->first();
        $all_revenues_forecasting = $planningRevenueOperatingAssumptions ? $planningRevenueOperatingAssumptions->all_revenues_forecasting : ['first_year'=>0,'second_year'=>0,'third_year'=>0];
        $all_revenues_costs_forecasting = $planningRevenueOperatingAssumptions ? $planningRevenueOperatingAssumptions->all_revenues_costs_forecasting : ['first_year'=>0,'second_year'=>0,'third_year'=>0];
        $planningCostAssumption = PlanningCostAssumption::where(['workspace_id' =>$this->user->workspace_id])
            ->first();
        $planningFinancialAssumption = PlanningFinancialAssumption::where('workspace_id', $this->user->workspace_id)
            ->first();
        $selected_navigation = 'IncomeList';

        $calc_total = $planningRevenueOperatingAssumptions->calc_total;

        return view('planning_revenue_operating_assumptions.getIncomeList',
            compact('calc_total','planningRevenueOperatingAssumptions','planningFinancialAssumption','planningCostAssumption','all_revenues_forecasting','all_revenues_costs_forecasting', 'selected_navigation'));
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
        $selected_navigation = 'statement_of_cash_flows';
        $calc_total = $planningRevenueOperatingAssumptions->calc_total;

        return view('planning_revenue_operating_assumptions.statement_of_cash_flows',
            compact('calc_total','planningRevenueOperatingAssumptions','planningFinancialAssumption','planningCostAssumption','all_revenues_forecasting','all_revenues_costs_forecasting', 'selected_navigation'));
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
        $totalInvestedCapital = (!empty($workingInvestedTotal) ? $workingInvestedTotal[0] : 0.0)+(!empty($fixedInvestedTotal) ? $fixedInvestedTotal[0] : 0.0);
        $totalInvestedCapital = formatCurrency($totalInvestedCapital,getWorkspaceCurrency($this->settings));
        $selected_navigation = 'capital_investment_model';
        $calc_total = $planningRevenueOperatingAssumptions->calc_total;

        return view('planning_revenue_operating_assumptions.capital_investment_model',
            compact('calc_total','planningRevenueOperatingAssumptions','planningFinancialAssumption','all_revenues_forecasting','all_revenues_costs_forecasting','totalInvestedCapital', 'selected_navigation'));
    }
    public function textReport(){
        $selected_navigation = 'textReport';
        $projectRevenues = ProjectRevenuePlanning::with(['sources'])
            ->where('workspace_id', $this->user->workspace_id)
            ->get();
        $planningRevenueOperatingAssumptions = PlanningRevenueOperatingAssumption::where('workspace_id', $this->user->workspace_id)
            ->first();
        $all_revenues_forecasting = $planningRevenueOperatingAssumptions ? $planningRevenueOperatingAssumptions->all_revenues_forecasting : ['first_year'=>0,'second_year'=>0,'third_year'=>0];
        $planningCostAssumption = PlanningCostAssumption::where(['workspace_id' =>$this->user->workspace_id])
            ->first();
        $all_revenues_costs_forecasting = $planningRevenueOperatingAssumptions ? $planningRevenueOperatingAssumptions->all_revenues_costs_forecasting : ['first_year'=>0,'second_year'=>0,'third_year'=>0];
        $planningFinancialAssumption = PlanningFinancialAssumption::where('workspace_id', $this->user->workspace_id)
            ->first();
        $workingInvestedTotal = WorkingInvestedCapital::select(DB::raw('SUM(investing_annual_cost) as investing_annual_cost_total'))->where("workspace_id", $this->user->workspace_id)->get()->pluck('investing_annual_cost_total');
        $fixedInvestedTotal = FixedInvestedCapital::select(DB::raw('SUM(investing_price) as investing_price_total'))->where("workspace_id", $this->user->workspace_id)->get()->pluck('investing_price_total');
        $totalInvestedCapital = (!empty($workingInvestedTotal) ? $workingInvestedTotal[0] : 0.0)+(!empty($fixedInvestedTotal) ? $fixedInvestedTotal[0] : 0.0);
        return view('planning_revenue_operating_assumptions.textReport',
            compact( 'selected_navigation',
                'projectRevenues',
                'all_revenues_forecasting',
                'planningCostAssumption',
                'all_revenues_costs_forecasting',
                'planningFinancialAssumption',
                'totalInvestedCapital'
            ));
    }
}
