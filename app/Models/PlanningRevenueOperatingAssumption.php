<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PlanningRevenueOperatingAssumption extends Model
{
    use HasFactory;
    protected $fillable = ['workspace_id', 'first_year', 'second_year', 'third_year'];

    public function getAllRevenuesDetailedForecastingAttribute()
    {
        $projectRevenuesPlanningList = [];
        $projectRevenuesPlanning = ProjectRevenuePlanning::with(['sources'])->where(['workspace_id' => Auth::user()->workspace_id])->get();
        foreach ($projectRevenuesPlanning as $projectRevenuePlanning) {
            $total_first_year = 0;
            $total_second_year = 0;
            $total_third_year = 0;

            foreach ($projectRevenuePlanning->sources as $source) {
                $total_first_year += ($source->total_revenue * ($this->first_year / 100));
                $total_second_year += (($source->total_revenue * ($this->first_year / 100)) + ($source->total_revenue * ($this->second_year / 100)));
                $total_third_year += (($source->total_revenue * ($this->first_year / 100)) + ($source->total_revenue * ($this->second_year / 100)) + ((($source->total_revenue * ($this->first_year / 100)) + ($source->total_revenue * ($this->second_year / 100))) * ($this->third_year / 100)));
            }
            array_push($projectRevenuesPlanningList, [
                "name" => $projectRevenuePlanning->name,
                'id' => $projectRevenuePlanning->id,
                'first_year' => $total_first_year,
                'second_year' => $total_second_year,
                'third_year' => $total_third_year,
            ]);
        }
        return $projectRevenuesPlanningList;
    }
    public function getAllRevenuesForecastingAttribute()
    {
        $all_revenues_detailed_forecasting = $this->all_revenues_detailed_forecasting;
        $total_first_year = 0;
        $total_second_year = 0;
        $total_third_year = 0;
        foreach ($all_revenues_detailed_forecasting as $projectRevenuePlanning) {
            $total_first_year += $projectRevenuePlanning['first_year'];
            $total_second_year += $projectRevenuePlanning['second_year'];
            $total_third_year += $projectRevenuePlanning['third_year'];
        }
        return [
            'first_year' => $total_first_year,
            'second_year' => $total_second_year,
            'third_year' => $total_third_year
        ];
    }
    public function getAllRevenuesCostsForecastingAttribute()
    {
        $planningCostAssumption = PlanningCostAssumption::where(['workspace_id' => Auth::user()->workspace_id])
            ->first();
        $all_revenues_detailed_forecasting = $this->all_revenues_detailed_forecasting;
        $total_costs_first_year = 0;
        $total_costs_second_year = 0;
        $total_costs_third_year = 0;
        foreach ($all_revenues_detailed_forecasting as $projectRevenuePlanning) {
            $total_costs_first_year += (($projectRevenuePlanning['first_year'] * ($planningCostAssumption->operational_costs / 100)) + ($projectRevenuePlanning['first_year'] * ($planningCostAssumption->general_expenses / 100)) + ($projectRevenuePlanning['first_year'] * ($planningCostAssumption->marketing_expenses / 100)));
            $total_costs_second_year += (($projectRevenuePlanning['second_year'] * ($planningCostAssumption->operational_costs / 100)) + ($projectRevenuePlanning['second_year'] * ($planningCostAssumption->general_expenses / 100)) + ($projectRevenuePlanning['second_year'] * ($planningCostAssumption->marketing_expenses / 100)));
            $total_costs_third_year += (($projectRevenuePlanning['third_year'] * ($planningCostAssumption->operational_costs / 100)) + ($projectRevenuePlanning['third_year'] * ($planningCostAssumption->general_expenses / 100)) + ($projectRevenuePlanning['third_year'] * ($planningCostAssumption->marketing_expenses / 100)));
        }
        return [
            'first_year' => $total_costs_first_year,
            'second_year' => $total_costs_second_year,
            'third_year' => $total_costs_third_year
        ];
    }
    public function getCalcTotalAttribute()
    {
        $user = User::where('super_admin', 1)->first();
        $settings_mod = Setting::where('workspace_id', $user->workspace_id)->get()->keyBy('key');
        if (isset($settings_mod['currency'])) {
            $currency = $settings_mod['currency']->value;
        } else {
            $currency = config('app.currency');
        }
        $settings_data = Setting::where(
            "workspace_id",
            Auth::user()->workspace_id
        )->get();
        $settings = [];

        foreach ($settings_data as $setting) {
            $settings[$setting->key] = $setting->value;
        }

        $planningRevenueOperatingAssumption = PlanningRevenueOperatingAssumption::query()->where('workspace_id', \auth()->user()->workspace_id)->get()->first();
        $percentage_first_year_operating_assumption = $planningRevenueOperatingAssumption->first_year / 100;
        $percentage_second_year_operating_assumption = $planningRevenueOperatingAssumption->second_year / 100;
        $percentage_third_year_operating_assumption = $planningRevenueOperatingAssumption->third_year / 100;

        $planningCostAssumption = PlanningCostAssumption::where(['workspace_id' => Auth::user()->workspace_id])
            ->first();

        $planningFinancialAssumption = PlanningFinancialAssumption::where('workspace_id', Auth::user()->workspace_id)
            ->first();
        $totalRevenueFirstYear = \App\Models\ProjectRevenuePlanning::calcTotalRevenueFirstYear();

        $totalRevenueSecondYear = \App\Models\ProjectRevenuePlanning::calcTotalRevenueSecondYear();
        $totalRevenueThirdYear = \App\Models\ProjectRevenuePlanning::calcTotalRevenueThirdYear();

        $first_year = ($totalRevenueFirstYear * $planningCostAssumption->operational_costs / 100) + ($totalRevenueFirstYear * $planningCostAssumption->general_expenses / 100) + ($totalRevenueFirstYear * $planningCostAssumption->marketing_expenses / 100);
        $second_year = ($totalRevenueSecondYear * $planningCostAssumption->operational_costs / 100) + ($totalRevenueSecondYear * $planningCostAssumption->general_expenses / 100) + ($totalRevenueSecondYear * $planningCostAssumption->marketing_expenses / 100);
        $third_year = ($totalRevenueThirdYear * $planningCostAssumption->operational_costs / 100) + ($totalRevenueThirdYear * $planningCostAssumption->general_expenses / 100) + ($totalRevenueThirdYear * $planningCostAssumption->marketing_expenses / 100);
        //added by abanoub

        // اجمالي التكاليف بعد افتراضات التشغيل
        $firstYearTotalAfterOperatingAssumption = $totalRevenueFirstYear * $percentage_first_year_operating_assumption;
        $secondYearTotalAfterOperatingAssumption = $totalRevenueSecondYear * $percentage_second_year_operating_assumption;
        $thirdYearTotalAfterOperatingAssumption = $totalRevenueThirdYear * $percentage_third_year_operating_assumption;

        $first_year_after_operating_assumption_as_string = formatCurrency($firstYearTotalAfterOperatingAssumption,$currency);
        $second_year_after_operating_assumption_as_string = formatCurrency($secondYearTotalAfterOperatingAssumption, 
    $currency);
        $third_year_after_operating_assumption_as_string = formatCurrency($thirdYearTotalAfterOperatingAssumption, $currency);


        // المصروفات التشغيلية
        $firstYearOperatingExpenses = $planningCostAssumption->operational_costs / 100 * $firstYearTotalAfterOperatingAssumption;
        $secondYearOperatingExpenses = $planningCostAssumption->operational_costs / 100 * $secondYearTotalAfterOperatingAssumption;
        $thirdYearOperatingExpenses = $planningCostAssumption->operational_costs / 100 * $thirdYearTotalAfterOperatingAssumption;

        $firstYearOperatingExpensesAsString = formatCurrency($firstYearOperatingExpenses, $currency);
        $secondYearOperatingExpensesAsString = formatCurrency($secondYearOperatingExpenses, $currency);
        $thirdYearOperatingExpensesAsString = formatCurrency($thirdYearOperatingExpenses, $currency);

        // المصروفات التسويقية
        // = نسبتها * الايرادات قبل افتراضات التشغيل
        $firstYearMarketingExpenses = $planningCostAssumption->marketing_expenses / 100 * $firstYearTotalAfterOperatingAssumption;
        $secondYearMarketingExpenses = $planningCostAssumption->marketing_expenses / 100 * $secondYearTotalAfterOperatingAssumption;
        $thirdYearMarketingExpenses = $planningCostAssumption->marketing_expenses / 100 * $thirdYearTotalAfterOperatingAssumption;

        $firstYearMarketingExpensesAsString = formatCurrency($firstYearMarketingExpenses, $currency);
        $secondYearMarketingExpensesAsString = formatCurrency($secondYearMarketingExpenses, $currency);
        $thirdYearMarketingExpensesAsString = formatCurrency($thirdYearMarketingExpenses, $currency);


        // مصروفات عمومية
        $firstYearGeneralExpenses = $planningCostAssumption->general_expenses / 100 * $firstYearTotalAfterOperatingAssumption;

        $secondYearGeneralExpenses = $planningCostAssumption->general_expenses / 100 * $secondYearTotalAfterOperatingAssumption;
        $thirdYearGeneralExpenses = $planningCostAssumption->general_expenses / 100 * $thirdYearTotalAfterOperatingAssumption;

        $firstYearGeneralExpensesAsString = formatCurrency($firstYearGeneralExpenses, $currency);
        $secondYearGeneralExpensesAsString = formatCurrency($secondYearGeneralExpenses, $currency);
        $thirdYearGeneralExpensesAsString = formatCurrency($thirdYearGeneralExpenses, $currency);

        //total cost
        $totalCostFirstYear = $firstYearGeneralExpenses + $firstYearMarketingExpenses + $firstYearOperatingExpenses;
        $totalCostFirstYearAsString = formatCurrency($totalCostFirstYear, $currency);

        $totalCostSecondYear = $secondYearGeneralExpenses + $secondYearMarketingExpenses + $secondYearOperatingExpenses;
        $totalCostSecondYearAsString = formatCurrency($totalCostSecondYear, $currency);

        $totalCostThirdYear = $thirdYearGeneralExpenses + $thirdYearMarketingExpenses + $thirdYearOperatingExpenses;
        $totalCostThirdYearAsString = formatCurrency($totalCostThirdYear, $currency);

        //end by abanoub
        $profit_before_zakat = ($totalRevenueFirstYear + $totalRevenueSecondYear + $totalRevenueThirdYear) - ($first_year + $second_year + $third_year);

        // اجمالي الايردات بعد افتراضات التشغيل - التكلفة لهذه السنه

        $first_year_profit_before_zakat = $firstYearTotalAfterOperatingAssumption - $totalCostFirstYear;
        $second_year_profit_before_zakat = $secondYearTotalAfterOperatingAssumption - $totalCostSecondYear;
        $third_year_profit_before_zakat = $thirdYearTotalAfterOperatingAssumption - $totalCostThirdYear;

        $firstYearProfitBeforeZakatAsString = formatCurrency($first_year_profit_before_zakat, $currency);
        $secondYearProfitBeforeZakatAsString = formatCurrency($second_year_profit_before_zakat, $currency);
        $thirdYearProfitBeforeZakatAsString = formatCurrency($third_year_profit_before_zakat, $currency);



        if ($first_year_profit_before_zakat < 0) {
            $totalProfitBeforeZakatAsNumber =  $second_year_profit_before_zakat + $third_year_profit_before_zakat;
        } else {
            $totalProfitBeforeZakatAsNumber = $first_year_profit_before_zakat + $second_year_profit_before_zakat + $third_year_profit_before_zakat;
        }

        $totalProfitBeforeZakatAsString = formatCurrency($totalProfitBeforeZakatAsNumber, getWorkspaceCurrency($settings));
        $totalOfZakat = $totalProfitBeforeZakatAsNumber * .025;
        $totalProfitAfterZakatAsNumber =  $totalProfitBeforeZakatAsNumber - $totalOfZakat;
        $totalProfitAfterZakatAsString = formatCurrency($totalProfitAfterZakatAsNumber, getWorkspaceCurrency($settings));

        //صافي الربح لكل سنه
        $zakatFirstYear = $first_year_profit_before_zakat > 0 ? $first_year_profit_before_zakat * .025 : 0;
        $zakatSecondYear = $second_year_profit_before_zakat * .025;
        $zakatThirdYear = $third_year_profit_before_zakat * .025;

        $firstYearNetProfit = $first_year_profit_before_zakat - $zakatFirstYear;
        $secondYearNetProfit = $second_year_profit_before_zakat - $zakatSecondYear;
        $thirdYearNetProfit = $third_year_profit_before_zakat - $zakatThirdYear;
        $investTotalCostFirstYear = $totalCostFirstYear  + $zakatFirstYear;

        $investTotalCostSecondYear = $totalCostSecondYear  + $zakatSecondYear;

        $investTotalCostThirdYear = $totalCostThirdYear  + $zakatThirdYear;

        return [
            // added by abanoub
            // operating expenses (مصروفات تشغيلية)
            'first_year_operating_expenses_as_number' => $firstYearOperatingExpenses,
            'second_year_operating_expenses_as_number' => $secondYearOperatingExpenses,
            'third_year_operating_expenses_as_number' => $thirdYearOperatingExpenses,
            'first_year_operating_expenses_as_string' => $firstYearOperatingExpensesAsString,
            'second_year_operating_expenses_as_string' => $secondYearOperatingExpensesAsString,
            'third_year_operating_expenses_as_string' => $thirdYearOperatingExpensesAsString,
            // مصروفات تسويقية
            'first_year_operating_marketing_as_number' => $firstYearMarketingExpenses,
            'second_year_operating_marketing_as_number' => $firstYearMarketingExpenses,
            'third_year_operating_marketing_as_number' => $firstYearMarketingExpenses,
            'first_year_operating_marketing_as_string' => $firstYearMarketingExpensesAsString,
            'second_year_operating_marketing_as_string' => $secondYearMarketingExpensesAsString,
            'third_year_operating_marketing_as_string' => $thirdYearMarketingExpensesAsString,
            // مصروفات عمومية
            'first_year_operating_general_as_number' => $firstYearGeneralExpenses,
            'second_year_operating_general_as_number' => $secondYearGeneralExpenses,
            'third_year_operating_general_as_number' => $thirdYearGeneralExpenses,
            'first_year_operating_general_as_string' => $firstYearGeneralExpensesAsString,
            'second_year_operating_general_as_string' => $secondYearGeneralExpensesAsString,
            'third_year_operating_general_as_string' => $thirdYearGeneralExpensesAsString,

            // اجمالي التكلفة
            'total_cost_first_year_as_number' => $totalCostFirstYear,
            'total_cost_second_year_as_number' => $totalCostSecondYear,
            'total_cost_third_year_as_number' => $totalCostThirdYear,
            'total_cost_first_year_as_string' => $totalCostFirstYearAsString,
            'total_cost_second_year_as_string' => $totalCostSecondYearAsString,
            'total_cost_third_year_as_string' => $totalCostThirdYearAsString,
            //اجمالي التكاليف بعد افتراضات التشغيل
            'first_year_after_operating_assumption_as_number' => $firstYearTotalAfterOperatingAssumption,
            'second_year_after_operating_assumption_as_number' => $secondYearTotalAfterOperatingAssumption,
            'third_year_after_operating_assumption_as_number' => $thirdYearTotalAfterOperatingAssumption,
            'first_year_after_operating_assumption_as_string' => $first_year_after_operating_assumption_as_string,
            'second_year_after_operating_assumption_as_string' => $second_year_after_operating_assumption_as_string,
            'third_year_after_operating_assumption_as_string' => $third_year_after_operating_assumption_as_string,
            //end
            // profit =
            'total_profit_before_zakat_as_number' => $totalProfitBeforeZakatAsNumber,
            'total_profit_before_zakat_as_string' => $totalProfitBeforeZakatAsString,
            'totalRevenueFirstYear' => formatCurrency($totalRevenueFirstYear, getWorkspaceCurrency($settings)),
            'totalRevenueSecondYear' => formatCurrency($totalRevenueSecondYear, getWorkspaceCurrency($settings)),
            'totalRevenueThirdYear' => formatCurrency($totalRevenueThirdYear, getWorkspaceCurrency($settings)),

            'first_year' => formatCurrency($first_year, getWorkspaceCurrency($settings)),
            'second_year' => formatCurrency($second_year, getWorkspaceCurrency($settings)),
            'third_year' => formatCurrency($third_year, getWorkspaceCurrency($settings)),

            'first_year_profit_before_zakat' => $firstYearProfitBeforeZakatAsString,
            'second_year_profit_before_zakat' => $secondYearProfitBeforeZakatAsString,
            'third_year_profit_before_zakat' => $thirdYearProfitBeforeZakatAsString,
            'first_year_profit_before_zakat_as_number' => $first_year_profit_before_zakat,
            'second_year_profit_before_zakat_as_number' => $second_year_profit_before_zakat,
            'third_year_profit_before_zakat_as_number' => $third_year_profit_before_zakat,

            'first_year_profit_before_zakat_percent_value' => formatCurrency($first_year_profit_before_zakat * 0.025, $currency),
            'second_year_profit_before_zakat_percent_value' => formatCurrency($second_year_profit_before_zakat * 0.025, $currency),
            'third_year_profit_before_zakat_percent_value' => formatCurrency($third_year_profit_before_zakat * 0.025, $currency),

            'first_year_profit_before_zakat_percent_number' => $first_year_profit_before_zakat * 0.025,
            'second_year_profit_before_zakat_percent_number' => $second_year_profit_before_zakat * 0.025,
            'third_year_profit_before_zakat_percent_number' => $third_year_profit_before_zakat * 0.025,

            'first_year_profit_after_zakat' => formatCurrency(($first_year_profit_before_zakat - $first_year_profit_before_zakat * 0.025), $currency),
            'second_year_profit_after_zakat' => formatCurrency(($second_year_profit_before_zakat - $second_year_profit_before_zakat * 0.025), $currency),
            'third_year_profit_after_zakat' => formatCurrency(($third_year_profit_before_zakat - $third_year_profit_before_zakat * 0.025), $currency),

            'pure_first_year_profit_after_zakat' => ($first_year_profit_before_zakat - $first_year_profit_before_zakat * 0.025),
            'pure_second_year_profit_after_zakat' => ($second_year_profit_before_zakat - $second_year_profit_before_zakat * 0.025),
            'pure_third_year_profit_after_zakat' => ($third_year_profit_before_zakat - $third_year_profit_before_zakat * 0.025),

            'profit_before_zakat' => formatCurrency($profit_before_zakat, getWorkspaceCurrency($settings)),
            'zakat_percent_value' => formatCurrency($profit_before_zakat * 0.025, getWorkspaceCurrency($settings)),
            'profit_after_zakat' => formatCurrency(($profit_before_zakat - $profit_before_zakat * 0.025), getWorkspaceCurrency($settings)),
            'net_zakat_value' => formatCurrency($profit_before_zakat - ($profit_before_zakat - $profit_before_zakat * 0.025), getWorkspaceCurrency($settings)),
            'first_year_net_cash_flow' => formatCurrency((($first_year_profit_before_zakat - $first_year_profit_before_zakat * 0.025) * ($planningFinancialAssumption->cash_percentage_of_net_profit / 100)), $currency),
            'second_year_net_cash_flow' => formatCurrency((($second_year_profit_before_zakat - $second_year_profit_before_zakat * 0.025) * ($planningFinancialAssumption->cash_percentage_of_net_profit / 100)), $currency),
            'third_year_net_cash_flow' => formatCurrency((($third_year_profit_before_zakat - $third_year_profit_before_zakat * 0.025) * ($planningFinancialAssumption->cash_percentage_of_net_profit / 100)), $currency),
            'first_year_net_cash_flow_number' => ($first_year_profit_before_zakat - $first_year_profit_before_zakat * 0.025) * ($planningFinancialAssumption->cash_percentage_of_net_profit / 100),
            'second_year_net_cash_flow_number' => ($second_year_profit_before_zakat - $second_year_profit_before_zakat * 0.025) * ($planningFinancialAssumption->cash_percentage_of_net_profit / 100),
            'third_year_net_cash_flow_number' => ($third_year_profit_before_zakat - $third_year_profit_before_zakat * 0.025) * ($planningFinancialAssumption->cash_percentage_of_net_profit / 100),
            //////////////////////////////////

            'total_profit_after_zakat_as_string' => $totalProfitAfterZakatAsString,
            'total_profit_after_zakat_as_number' => $totalProfitAfterZakatAsNumber,
            'first_year_capital_change' => formatCurrency(((($first_year_profit_before_zakat - $first_year_profit_before_zakat * 0.025) * ($planningFinancialAssumption->cash_percentage_of_net_profit / 100)) - ($first_year_profit_before_zakat - $first_year_profit_before_zakat * 0.025)), getWorkspaceCurrency($settings)),
            'second_year_capital_change' => formatCurrency(((($second_year_profit_before_zakat - $second_year_profit_before_zakat * 0.025) * ($planningFinancialAssumption->cash_percentage_of_net_profit / 100)) - ($second_year_profit_before_zakat - $second_year_profit_before_zakat * 0.025)), getWorkspaceCurrency($settings)),
            'third_year_capital_change' => formatCurrency(((($third_year_profit_before_zakat - $third_year_profit_before_zakat * 0.025) * ($planningFinancialAssumption->cash_percentage_of_net_profit / 100)) - ($third_year_profit_before_zakat - $third_year_profit_before_zakat * 0.025)), getWorkspaceCurrency($settings)),

            'first_year_costs' => formatCurrency(($first_year + $first_year_profit_before_zakat * 0.025), getWorkspaceCurrency($settings)),
            'second_year_costs' => formatCurrency(($second_year + $second_year_profit_before_zakat * 0.025), getWorkspaceCurrency($settings)),
            'third_year_costs' => formatCurrency(($third_year + $third_year_profit_before_zakat * 0.025), getWorkspaceCurrency($settings)),

            'first_year_cash_flow' => formatCurrency(((($first_year_profit_before_zakat - ($first_year_profit_before_zakat * 0.025)) - $first_year_profit_before_zakat) * ($planningFinancialAssumption->cash_percentage_of_net_profit / 100)), getWorkspaceCurrency($settings)),
            'second_year_cash_flow' => formatCurrency(((($second_year_profit_before_zakat - ($second_year_profit_before_zakat * 0.025)) - $second_year_profit_before_zakat) * ($planningFinancialAssumption->cash_percentage_of_net_profit / 100)), getWorkspaceCurrency($settings)),
            'third_year_cash_flow' => formatCurrency(((($third_year_profit_before_zakat - ($third_year_profit_before_zakat * 0.025)) - $third_year_profit_before_zakat) * ($planningFinancialAssumption->cash_percentage_of_net_profit / 100)), getWorkspaceCurrency($settings)),
            'zakat_first_year_value_as_string' => formatCurrency($zakatFirstYear, getWorkspaceCurrency($settings)),
            'zakat_second_year_value_as_string' => formatCurrency($zakatSecondYear, getWorkspaceCurrency($settings)),
            'zakat_third_year_value_as_string' => formatCurrency($zakatThirdYear, getWorkspaceCurrency($settings)),
            'zakat_first_year_as_number' => $zakatFirstYear,
            'zakat_second_year_as_number' => $zakatSecondYear,
            'zakat_third_year_as_number' => $zakatThirdYear,
            'net_profit_first_year_as_string' => formatCurrency($firstYearNetProfit, $currency),
            'net_profit_second_year_as_string' => formatCurrency($secondYearNetProfit, $currency),
            'net_profit_third_year_as_string' => formatCurrency($thirdYearNetProfit, getWorkspaceCurrency($settings)),
            'net_profit_first_year_as_number' => $firstYearNetProfit,
            'net_profit_second_year_as_number' => $secondYearNetProfit,
            'net_profit_third_year_as_number' => $thirdYearNetProfit,
            'invest_total_cost_first_year_as_string' => formatCurrency($investTotalCostFirstYear, getWorkspaceCurrency($settings)),
            'invest_total_cost_second_year_as_string' => formatCurrency($investTotalCostSecondYear, getWorkspaceCurrency($settings)),
            'invest_total_cost_third_year_as_string' => formatCurrency($investTotalCostThirdYear, getWorkspaceCurrency($settings)),
            'invest_total_cost_first_year_as_number' => $investTotalCostFirstYear,
            'invest_total_cost_second_year_as_number' => $investTotalCostSecondYear,
            'invest_total_cost_third_year_as_number' => $investTotalCostThirdYear,
        ];
    }
}
