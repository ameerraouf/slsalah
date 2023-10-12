<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\FinancialProjections\PlanningCostAssumptionsRequest;
use App\Models\PlanningCostAssumption;
use Illuminate\Http\Request;

class PlanningCostAssumptionsController extends BaseController
{

    public function index(){
        if ($this->modules && !in_array("planning_cost_assumptions", $this->modules)) {
            abort(403);
        }
        $planningCostAssumption = PlanningCostAssumption::where(['workspace_id' =>$this->user->workspace_id])->first();

        return \view("planning_cost_assumptions.index", [
            "selected_navigation" => "planning_cost_assumptions",
            "planningCostAssumption" => $planningCostAssumption
        ]);
    }
    public function store(PlanningCostAssumptionsRequest $request){
        if(!empty($this->user->workspace_id)) {
//            dd(($request->operational_costs + $request->general_expenses + $request->marketing_expenses));
            if(($request->operational_costs + $request->general_expenses + $request->marketing_expenses) >= 100){
                return response()->json(['msg' => __('totalMustLesserThan100'), 'type' => 'error']);
            }
            $planningCostAssumptionExistence = PlanningCostAssumption::where('workspace_id', $this->user->workspace_id)
                ->first();
            if ($planningCostAssumptionExistence) {
                $planningCostAssumptionExistence->update([
                    'operational_costs' => $request->operational_costs,
                    'general_expenses' => $request->general_expenses,
                    'marketing_expenses' => $request->marketing_expenses
                ]);
            } else {
                PlanningCostAssumption::create([
                    'operational_costs' => $request->operational_costs,
                    'general_expenses' => $request->general_expenses,
                    'marketing_expenses' => $request->marketing_expenses,
                    'workspace_id' => $this->user->workspace_id
                ]);
            }
            return response()->json(['msg' => __('planningCostAssumptionsStored'), 'type' => 'success']);
        }else{
            return response()->json(['msg' => __('noUserWorkspaceFound'), 'type' => 'error']);
        }
    }
}
