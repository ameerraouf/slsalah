<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PlanningRevenueOperatingAssumption extends Model
{
    use HasFactory;
    protected $fillable = ['workspace_id','first_year','second_year','third_year'];

    public function getAllRevenuesDetailedForecastingAttribute(){
        $projectRevenuesPlanningList = [];
        $projectRevenuesPlanning = ProjectRevenuePlanning::with(['sources'])->where(['workspace_id' =>Auth::user()->workspace_id])->get();
        foreach($projectRevenuesPlanning as $projectRevenuePlanning) {
            $total_first_year = 0;
            $total_second_year = 0;
            $total_third_year = 0;

            foreach ($projectRevenuePlanning->sources as $source) {
                $total_first_year += ($source->total_revenue * ($this->first_year / 100));
                $total_second_year += (($source->total_revenue * ($this->first_year / 100)) + ($source->total_revenue * ($this->second_year / 100)));
                $total_third_year += (($source->total_revenue * ($this->first_year / 100)) + ($source->total_revenue * ($this->second_year / 100)) + ((($source->total_revenue * ($this->first_year / 100)) + ($source->total_revenue * ($this->second_year / 100))) * ($this->third_year / 100)));
            }
            array_push($projectRevenuesPlanningList,[
                "name"=>$projectRevenuePlanning->name,
                'id'=>$projectRevenuePlanning->id,
                'first_year'=>$total_first_year,
                'second_year'=>$total_second_year,
                'third_year'=>$total_third_year,
            ]);
        }
        return $projectRevenuesPlanningList;
    }
    public function getAllRevenuesForecastingAttribute(){
        $all_revenues_detailed_forecasting = $this->all_revenues_detailed_forecasting;
        $total_first_year = 0;
        $total_second_year = 0;
        $total_third_year = 0;
        foreach($all_revenues_detailed_forecasting as $projectRevenuePlanning) {
            $total_first_year += $projectRevenuePlanning['first_year'];
            $total_second_year += $projectRevenuePlanning['second_year'];
            $total_third_year += $projectRevenuePlanning['third_year'];
        }
        return [
            'first_year'=>$total_first_year,
            'second_year'=>$total_second_year,
            'third_year'=>$total_third_year
        ];
    }
    public function getAllRevenuesCostsForecastingAttribute(){
        $planningCostAssumption = PlanningCostAssumption::where(['workspace_id' =>Auth::user()->workspace_id])
            ->first();
        $all_revenues_detailed_forecasting = $this->all_revenues_detailed_forecasting;
        $total_costs_first_year = 0;
        $total_costs_second_year = 0;
        $total_costs_third_year = 0;
        foreach($all_revenues_detailed_forecasting as $projectRevenuePlanning) {
            $total_costs_first_year += (($projectRevenuePlanning['first_year']*($planningCostAssumption->operational_costs / 100)) + ($projectRevenuePlanning['first_year']*($planningCostAssumption->general_expenses / 100)) + ($projectRevenuePlanning['first_year']*($planningCostAssumption->marketing_expenses / 100)));
            $total_costs_second_year += (($projectRevenuePlanning['second_year']*($planningCostAssumption->operational_costs / 100)) + ($projectRevenuePlanning['second_year']*($planningCostAssumption->general_expenses / 100)) + ($projectRevenuePlanning['second_year']*($planningCostAssumption->marketing_expenses / 100)));
            $total_costs_third_year += (($projectRevenuePlanning['third_year']*($planningCostAssumption->operational_costs / 100)) + ($projectRevenuePlanning['third_year']*($planningCostAssumption->general_expenses / 100)) + ($projectRevenuePlanning['third_year']*($planningCostAssumption->marketing_expenses / 100)));
        }
        return [
            'first_year'=>$total_costs_first_year,
            'second_year'=>$total_costs_second_year,
            'third_year'=>$total_costs_third_year
        ];
    }
    public function getCalcTotalAttribute(){
        $settings_data = Setting::where(
            "workspace_id",
            Auth::user()->workspace_id
        )->get();
        $settings = [];

        foreach ($settings_data as $setting) {
            $settings[$setting->key] = $setting->value;
        }
        $planningCostAssumption = PlanningCostAssumption::where(['workspace_id' =>Auth::user()->workspace_id])
            ->first();
        $planningFinancialAssumption = PlanningFinancialAssumption::where('workspace_id', Auth::user()->workspace_id)
            ->first();
        $totalRevenueFirstYear = \App\Models\ProjectRevenuePlanning::calcTotalRevenueFirstYear();
        $totalRevenueSecondYear = \App\Models\ProjectRevenuePlanning::calcTotalRevenueSecondYear();
        $totalRevenueThirdYear = \App\Models\ProjectRevenuePlanning::calcTotalRevenueThirdYear();

        $first_year = ($totalRevenueFirstYear * $planningCostAssumption->operational_costs / 100) + ($totalRevenueFirstYear * $planningCostAssumption->general_expenses / 100) + ($totalRevenueFirstYear * $planningCostAssumption->marketing_expenses / 100);
        $second_year = ($totalRevenueSecondYear * $planningCostAssumption->operational_costs / 100) + ($totalRevenueSecondYear * $planningCostAssumption->general_expenses / 100) + ($totalRevenueSecondYear * $planningCostAssumption->marketing_expenses / 100);
        $third_year = ($totalRevenueThirdYear * $planningCostAssumption->operational_costs / 100) + ($totalRevenueThirdYear * $planningCostAssumption->general_expenses / 100) + ($totalRevenueThirdYear * $planningCostAssumption->marketing_expenses / 100);

        $profit_before_zakat = ( $totalRevenueFirstYear+ $totalRevenueSecondYear+ $totalRevenueThirdYear) - ( $first_year+ $second_year+ $third_year);

        $first_year_profit_before_zakat = $totalRevenueFirstYear - $first_year;
        $second_year_profit_before_zakat = $totalRevenueSecondYear - $second_year;
        $third_year_profit_before_zakat = $totalRevenueThirdYear - $third_year;
        return [
            'totalRevenueFirstYear'=>formatCurrency($totalRevenueFirstYear ,getWorkspaceCurrency($settings)),
            'totalRevenueSecondYear'=>formatCurrency($totalRevenueSecondYear ,getWorkspaceCurrency($settings)),
            'totalRevenueThirdYear'=>formatCurrency($totalRevenueThirdYear ,getWorkspaceCurrency($settings)),

            'first_year' => formatCurrency($first_year ,getWorkspaceCurrency($settings)),
            'second_year'=> formatCurrency($second_year ,getWorkspaceCurrency($settings)),
            'third_year' => formatCurrency($third_year ,getWorkspaceCurrency($settings)),

            'first_year_profit_before_zakat' => formatCurrency($first_year_profit_before_zakat ,getWorkspaceCurrency($settings)),
            'second_year_profit_before_zakat' => formatCurrency($second_year_profit_before_zakat ,getWorkspaceCurrency($settings)),
            'third_year_profit_before_zakat' => formatCurrency($third_year_profit_before_zakat ,getWorkspaceCurrency($settings)),

            'first_year_profit_before_zakat_percent_value' => formatCurrency($first_year_profit_before_zakat*0.025 ,getWorkspaceCurrency($settings)),
            'second_year_profit_before_zakat_percent_value' => formatCurrency($second_year_profit_before_zakat*0.025 ,getWorkspaceCurrency($settings)),
            'third_year_profit_before_zakat_percent_value' => formatCurrency($third_year_profit_before_zakat*0.025 ,getWorkspaceCurrency($settings)),

            'first_year_profit_after_zakat' => formatCurrency(($first_year_profit_before_zakat - $first_year_profit_before_zakat*0.025) ,getWorkspaceCurrency($settings)),
            'second_year_profit_after_zakat' => formatCurrency(($second_year_profit_before_zakat - $second_year_profit_before_zakat*0.025) ,getWorkspaceCurrency($settings)),
            'third_year_profit_after_zakat' => formatCurrency(($third_year_profit_before_zakat - $third_year_profit_before_zakat*0.025) ,getWorkspaceCurrency($settings)),

            'pure_first_year_profit_after_zakat'=>($first_year_profit_before_zakat - $first_year_profit_before_zakat*0.025),
            'pure_second_year_profit_after_zakat'=>($second_year_profit_before_zakat - $second_year_profit_before_zakat*0.025),
            'pure_third_year_profit_after_zakat'=>($third_year_profit_before_zakat - $third_year_profit_before_zakat*0.025),

            'profit_before_zakat'=> formatCurrency($profit_before_zakat,getWorkspaceCurrency($settings)),
            'zakat_percent_value'=>formatCurrency($profit_before_zakat*0.025,getWorkspaceCurrency($settings)),
            'profit_after_zakat'=>formatCurrency(($profit_before_zakat - $profit_before_zakat*0.025),getWorkspaceCurrency($settings)),

            'first_year_net_cash_flow' => formatCurrency((($first_year_profit_before_zakat - $first_year_profit_before_zakat*0.025) * ($planningFinancialAssumption->cash_percentage_of_net_profit/100)) ,getWorkspaceCurrency($settings)),
            'second_year_net_cash_flow' => formatCurrency((($second_year_profit_before_zakat - $second_year_profit_before_zakat*0.025) * ($planningFinancialAssumption->cash_percentage_of_net_profit/100)) ,getWorkspaceCurrency($settings)),
            'third_year_net_cash_flow' => formatCurrency((($third_year_profit_before_zakat - $third_year_profit_before_zakat*0.025) * ($planningFinancialAssumption->cash_percentage_of_net_profit/100)) ,getWorkspaceCurrency($settings)),

                //////////////////////////////////

            'first_year_capital_change' => formatCurrency(((($first_year_profit_before_zakat - $first_year_profit_before_zakat*0.025) * ($planningFinancialAssumption->cash_percentage_of_net_profit/100)) - ($first_year_profit_before_zakat - $first_year_profit_before_zakat*0.025)) ,getWorkspaceCurrency($settings)),
            'second_year_capital_change' => formatCurrency(((($second_year_profit_before_zakat - $second_year_profit_before_zakat*0.025) * ($planningFinancialAssumption->cash_percentage_of_net_profit/100)) - ($second_year_profit_before_zakat - $second_year_profit_before_zakat*0.025)) ,getWorkspaceCurrency($settings)),
            'third_year_capital_change' => formatCurrency(((($third_year_profit_before_zakat - $third_year_profit_before_zakat*0.025) * ($planningFinancialAssumption->cash_percentage_of_net_profit/100)) - ($third_year_profit_before_zakat - $third_year_profit_before_zakat*0.025)),getWorkspaceCurrency($settings)),

            'first_year_costs'=>formatCurrency(($first_year + $first_year_profit_before_zakat*0.025),getWorkspaceCurrency($settings)),
            'second_year_costs'=>formatCurrency(($second_year + $second_year_profit_before_zakat*0.025),getWorkspaceCurrency($settings)),
            'third_year_costs'=>formatCurrency(($third_year + $third_year_profit_before_zakat*0.025),getWorkspaceCurrency($settings)),

            'first_year_cash_flow'=>formatCurrency(((($first_year_profit_before_zakat - ($first_year_profit_before_zakat*0.025))-$first_year_profit_before_zakat) * ($planningFinancialAssumption->cash_percentage_of_net_profit/100)),getWorkspaceCurrency($settings)),
            'second_year_cash_flow'=>formatCurrency(((($second_year_profit_before_zakat - ($second_year_profit_before_zakat*0.025))-$second_year_profit_before_zakat) * ($planningFinancialAssumption->cash_percentage_of_net_profit/100)),getWorkspaceCurrency($settings)),
            'third_year_cash_flow'=>formatCurrency(((($third_year_profit_before_zakat - ($third_year_profit_before_zakat*0.025))-$third_year_profit_before_zakat) * ($planningFinancialAssumption->cash_percentage_of_net_profit/100)),getWorkspaceCurrency($settings))
        ];
    }
}
