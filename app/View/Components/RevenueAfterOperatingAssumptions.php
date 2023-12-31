<?php

namespace App\View\Components;

use App\Models\PlanningCostAssumption;
use App\Models\PlanningFinancialAssumption;
use App\Models\PlanningRevenueOperatingAssumption;
use App\Models\ProjectRevenuePlanning;
use Illuminate\View\Component;

class RevenueAfterOperatingAssumptions extends Component
{
    public $planningRevenueOperatingAssumptions;
    public $all_revenues_forecasting;
    public $all_revenues_costs_forecasting;

    public $planningCostAssumption;

    public $planningFinancialAssumption;

    public $calc_total;

    public $projectRevenuesPlanning;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->planningRevenueOperatingAssumptions = PlanningRevenueOperatingAssumption::where('workspace_id', auth()->user()->workspace_id)
            ->first();
        $this->all_revenues_forecasting = $this->planningRevenueOperatingAssumptions ? $this->planningRevenueOperatingAssumptions->all_revenues_forecasting : ['first_year'=>0,'second_year'=>0,'third_year'=>0];
        $this->all_revenues_costs_forecasting = $this->planningRevenueOperatingAssumptions ? $this->planningRevenueOperatingAssumptions->all_revenues_costs_forecasting : ['first_year'=>0,'second_year'=>0,'third_year'=>0];
        $this->planningCostAssumption = PlanningCostAssumption::where(['workspace_id' =>auth()->user()->workspace_id])
            ->first();
        $this->planningFinancialAssumption = PlanningFinancialAssumption::where('workspace_id', auth()->user()->workspace_id)
            ->first();
        if($this->planningRevenueOperatingAssumptions){
            $this->calc_total = $this->planningRevenueOperatingAssumptions->calc_total;
        }else{
            $this->calc_total =[];
        }


        $this->projectRevenuesPlanning = ProjectRevenuePlanning::with(['sources'])->where(['workspace_id' =>auth()->user()->workspace_id])->get();

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.revenue-after-operating-assumptions', [
            'projectRevenuesPlanning' => $this->projectRevenuesPlanning,
        ]);
    }
}
