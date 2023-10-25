<?php

namespace App\Http\Controllers;

use App\Models\FixedInvestedCapital;
use App\Models\PlanningCostAssumption;
use App\Models\PlanningRevenueOperatingAssumption;
use App\Models\ProjectRevenuePlanning;
use App\Models\Task;
use App\Models\TaskGoal;
use App\Models\WorkingInvestedCapital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use mikehaertl\wkhtmlto\Pdf;

class MyPlanController extends BaseController
{
    //

    public function index(){
        $data = [];
        $data['selected_navigation'] = "billing";
        $data['projectRevenues'] = ProjectRevenuePlanning::with(['sources'])
            ->where('workspace_id', $this->user->workspace_id)
            ->get();
        $data['tasks'] = Task::where(
            "workspace_id",
            $this->user->workspace_id
        )->get();
        $data['task_goal'] = TaskGoal::where(
            "workspace_id",
            $this->user->workspace_id
        )->first();
        $data['fixedInvested'] = FixedInvestedCapital::where("workspace_id", $this->user->workspace_id)->get();
        $data['workingInvested'] = WorkingInvestedCapital::where("workspace_id", $this->user->workspace_id)->get();
        $fixedChart = [];
        $workingChart = [];
        foreach ($data['fixedInvested'] as $key =>$item){
            $fixedChart[] = ['y' => $item->investing_price, 'label' => $key+1 . ' - '. $item->investing_description . ' : ' . $item->investing_price. ' ريال سعودي' ];
        }
        foreach ($data['workingInvested'] as $key =>$item){
            $workingChart[] = ['y' => $item->investing_annual_cost, 'label' => $item->investing_description . ' : ' .$item->investing_annual_cost . ' ريال سعودي'];
        }
        $data['fixedChart'] = $fixedChart;
        $data['workingChart'] = $workingChart;
        $data['planningCostAssumption'] = PlanningCostAssumption::where(['workspace_id' =>$this->user->workspace_id])
            ->first();
        $data['planningRevenueOperatingAssumptions'] = PlanningRevenueOperatingAssumption::where('workspace_id', $this->user->workspace_id)
            ->first();
        if($data['planningRevenueOperatingAssumptions']){
            $data['calc_total'] = $data['planningRevenueOperatingAssumptions']->calc_total;
        }else{
            $data['calc_total'] = [];
        }

        $workingInvestedTotal = WorkingInvestedCapital::select(DB::raw('SUM(investing_annual_cost) as investing_annual_cost_total'))->where("workspace_id", $this->user->workspace_id)->get()->pluck('investing_annual_cost_total');
        $fixedInvestedTotal = FixedInvestedCapital::select(DB::raw('SUM(investing_price) as investing_price_total'))->where("workspace_id", $this->user->workspace_id)->get()->pluck('investing_price_total');
        $totalInvestedCapital = (!empty($workingInvestedTotal) ? $workingInvestedTotal[0] : 0.0)+(!empty($fixedInvestedTotal) ? $fixedInvestedTotal[0] : 0.0);
        $data['totalInvestedCapital'] = formatCurrency($totalInvestedCapital,getWorkspaceCurrency($this->settings));


        foreach ($data['workingChart'] as $key => $value) {
            $data['workingChart'][$key]['label'] = ($key + 1) . '- SAR ' . str_replace('ريال سعودي', '', $value['label']);
        }


        foreach ($data['fixedChart'] as &$value) {
            $value = str_replace('ريال سعودي', '', $value);
            $value = str_replace('-', '- SAR', $value);
        }

        return view('myPlane.index', $data);
    }
}
