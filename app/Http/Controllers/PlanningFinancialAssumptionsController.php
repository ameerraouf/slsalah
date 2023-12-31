<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\FinancialProjections\PlanningFinancialAssumptionsRequest;
use App\Models\PlanningCostAssumption;
use App\Models\PlanningFinancialAssumption;
use Illuminate\Http\Request;

class PlanningFinancialAssumptionsController extends BaseController
{

    public function index(){
        if ($this->modules && !in_array("planning_cost_assumptions", $this->modules)) {
            abort(403);
        }
        $planningFinancialAssumption = PlanningFinancialAssumption::where(['workspace_id' =>$this->user->workspace_id])->first();

        $planningCostAssumption = PlanningCostAssumption::where(['workspace_id' =>$this->user->workspace_id])->first();
        $planningCostAssumptionTotalRemainingValue = 100;
        if($planningCostAssumption) {
            $planningCostAssumptionTotalRemainingValue -= $planningCostAssumption->operational_costs + $planningCostAssumption->general_expenses + $planningCostAssumption->marketing_expenses;
        }
        $planningCostAssumptionNetProfitHint = ($planningCostAssumptionTotalRemainingValue == 100 || empty($planningCostAssumptionTotalRemainingValue)) ? 'يرجى اضافة قيم منطقية لتخطيط افتراضات التكاليف' : 'القيمة الصحيحة لصافي الربح هي : '.$planningCostAssumptionTotalRemainingValue;
        return \view("planning_financial_assumptions.index", [
            "selected_navigation" => "planning_financial_assumptions",
            "planningFinancialAssumption" => $planningFinancialAssumption,
            "planningCostAssumptionNetProfitHint" => $planningCostAssumptionNetProfitHint,
            "planningCostAssumptionTotalRemainingValue" => $planningCostAssumptionTotalRemainingValue
        ]);
    }
    public function store(PlanningFinancialAssumptionsRequest $request){
        if(!empty($this->user->workspace_id)) {
            $planningFinancialAssumptionExistence = PlanningFinancialAssumption::where('workspace_id', $this->user->workspace_id)
                ->first();
            if ($planningFinancialAssumptionExistence) {
                $planningFinancialAssumptionExistence->update([
                    'net_profit' => $request->net_profit,
                    'cash_percentage_of_net_profit' => $request->cash_percentage_of_net_profit
                ]);
            } else {
                PlanningFinancialAssumption::create([
                    'net_profit' => $request->net_profit,
                    'cash_percentage_of_net_profit' => $request->cash_percentage_of_net_profit,
                    'workspace_id' => $this->user->workspace_id
                ]);
            }
            return response()->json(['msg' => __('planningFinancialAssumptionsStored'), 'type' => 'success']);
        }else{
            return response()->json(['msg' => __('noUserWorkspaceFound'), 'type' => 'error']);
        }
    }
}
