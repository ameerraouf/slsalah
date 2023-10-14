@extends('layouts.primary')
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card card-body mb-4">
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="row">
                        <p> التقارير النصية</p>
                    </div>
                    <div class="table-responsive p-0">
                        @php
                            $revenues = '';
                            foreach ($projectRevenues as $revenue){
                                $revenues .= $revenue->name. ' و ';
                            }
                            $totalCostFirstYear = $all_revenues_forecasting['first_year'] * ($planningCostAssumption->general_expenses / 100) +$all_revenues_forecasting['first_year'] * ($planningCostAssumption->operational_costs / 100) +$all_revenues_forecasting['first_year'] * ($planningCostAssumption->marketing_expenses / 100);
                            $totalCostSecondYear = $all_revenues_forecasting['second_year'] * ($planningCostAssumption->general_expenses / 100) +$all_revenues_forecasting['second_year'] * ($planningCostAssumption->operational_costs / 100) +$all_revenues_forecasting['second_year'] * ($planningCostAssumption->marketing_expenses / 100);
                            $totalCostThirdYear = $all_revenues_forecasting['third_year'] * ($planningCostAssumption->general_expenses / 100) +$all_revenues_forecasting['third_year'] * ($planningCostAssumption->operational_costs / 100) +$all_revenues_forecasting['third_year'] * ($planningCostAssumption->marketing_expenses / 100);
                            $first_year_profit = $all_revenues_forecasting['first_year'] - $all_revenues_costs_forecasting['first_year'];
                            $second_year_profit = $all_revenues_forecasting['second_year'] - $all_revenues_costs_forecasting['second_year'];
                            $third_year_profit = $all_revenues_forecasting['third_year'] - $all_revenues_costs_forecasting['third_year'];
                            $first_year_net_cash_flow = ($first_year_profit - ($first_year_profit*0.025))* ($planningFinancialAssumption->cash_percentage_of_net_profit/100);
                            $second_year_net_cash_flow = ($second_year_profit - ($second_year_profit*0.025))* ($planningFinancialAssumption->cash_percentage_of_net_profit/100);
                            $third_year_net_cash_flow = ($third_year_profit - ($third_year_profit*0.025))* ($planningFinancialAssumption->cash_percentage_of_net_profit/100);
                            $first_year_profit_margin = $all_revenues_forecasting['first_year'] - $all_revenues_costs_forecasting['first_year'];
                            $second_year_profit_margin = $all_revenues_forecasting['second_year'] - $all_revenues_costs_forecasting['second_year'];
                            $third_year_profit_margin = $all_revenues_forecasting['third_year'] - $all_revenues_costs_forecasting['third_year'];
                            $first_year_zakat = $first_year_profit_margin * 0.025;
                            $second_year_zakat = $second_year_profit_margin * 0.025;
                            $third_year_zakat = $third_year_profit_margin * 0.025;
                        @endphp
                        <p>*يمكن تحقيق بواسطة {{ $revenues }} سيحقق في السنة الأولى (({{ \App\Models\ProjectRevenuePlanning::calcTotalRevenueFirstYear() . ' '. getWorkspaceCurrency($settings)}} وسيحقق في السنة الثانية ({{ \App\Models\ProjectRevenuePlanning::calcTotalRevenueSecondYear() . ' '. getWorkspaceCurrency($settings)}}) وسيحقق في السنة الثالثة ({{ \App\Models\ProjectRevenuePlanning::calcTotalRevenueThirdYear() . ' '. getWorkspaceCurrency($settings)}})</p>
                        <p> **وسيكون هناك مصروفات إدارية وعمومية في السنة الأولى بقيمة ({{  formatCurrency((\App\Models\ProjectRevenuePlanning::calcTotalRevenueFirstYear() * $planningCostAssumption->general_expenses / 100),getWorkspaceCurrency($settings))  }} ) والتي تمثل {{ $planningCostAssumption->general_expenses }}% من إجمالي الإيرادات وأيضا في السنة الثانية بقيمة ({{  formatCurrency((\App\Models\ProjectRevenuePlanning::calcTotalRevenueSecondYear() * $planningCostAssumption->general_expenses / 100),getWorkspaceCurrency($settings))  }}) وأيضا في السنة الثالثة بقيمة ({{ formatCurrency((\App\Models\ProjectRevenuePlanning::calcTotalRevenueThirdYear() * $planningCostAssumption->general_expenses / 100),getWorkspaceCurrency($settings)) }})</p>
                        <p> **وسيكون هناك مصروفات تشغيلية في السنة الأولى بقيمة ({{  formatCurrency((\App\Models\ProjectRevenuePlanning::calcTotalRevenueFirstYear() * $planningCostAssumption->operational_costs / 100) ,getWorkspaceCurrency($settings)) }}) والتي تمثل {{ $planningCostAssumption->operational_costs }}% من إجمالي الإيرادات وأيضا في السنة الثانية بقيمة ({{   formatCurrency((\App\Models\ProjectRevenuePlanning::calcTotalRevenueSecondYear() * $planningCostAssumption->operational_costs / 100) ,getWorkspaceCurrency($settings))  }} ) وأيضا في السنة الثالثة بقيمة ({{ formatCurrency((\App\Models\ProjectRevenuePlanning::calcTotalRevenueThirdYear() * $planningCostAssumption->operational_costs / 100) ,getWorkspaceCurrency($settings)) }})</p>
                        <p>***وسيكون هناك مصروفات تسويقية في السنة الأولى بقيمة ({{ formatCurrency((\App\Models\ProjectRevenuePlanning::calcTotalRevenueFirstYear() * $planningCostAssumption->marketing_expenses / 100) ,getWorkspaceCurrency($settings)) }} ) والتي تمثل {{ $planningCostAssumption->marketing_expenses }}% من إجمالي الإيرادات وأيضا في السنة الثانية بقيمة ({{  formatCurrency((\App\Models\ProjectRevenuePlanning::calcTotalRevenueSecondYear() * $planningCostAssumption->marketing_expenses / 100) ,getWorkspaceCurrency($settings)) }}) وأيضا في السنة الثالثة بقيمة ({{ formatCurrency((\App\Models\ProjectRevenuePlanning::calcTotalRevenueThirdYear() * $planningCostAssumption->marketing_expenses / 100) ,getWorkspaceCurrency($settings))}})</p>
                        <p>وبالتالي فإن إجمالي التكاليف في السنة الأولى سيكون ( {{ $calc_total['first_year'] }}) وفي السنة الثانية سيكون ( {{ $calc_total['second_year'] }}) وفي السنة الثالثة سيكون ( {{ $calc_total['third_year'] }})</p>
                        <p>***بالنسبة للربح قبل الزكاة EBIT  سيكون في السنة الأولى ({{$calc_total['first_year_profit_before_zakat']}}) وفي السنة الثانية ({{$calc_total['second_year_profit_before_zakat']}}) وفي السنة الثالثة  ({{$calc_total['third_year_profit_before_zakat']}}).</p>
                        <p>***وقيمة الزكاة ستكون (0 SAR)  في السنة الأولى في حالة وجود عدم ربح وستكون ({{$calc_total['second_year_profit_before_zakat_percent_value']}}) في السنة الثانية و ستكون ({{$calc_total['third_year_profit_before_zakat_percent_value']}}) في السنة الثالثة .</p>


                        <p>***بالنسبة لصافي الربح  EAT فستكون قيمتة في السنة الأولى ( {{$calc_total['first_year_profit_after_zakat'] }}) وفي السنة الثانية ( {{ $calc_total['second_year_profit_after_zakat'] }}) وفي السنة الثالثة ( {{ $calc_total['third_year_profit_after_zakat'] }}) .</p>
                        <p>***بالنسبة لقائمة التدفقات النقدية من الأنشطة التشغيلية فصافي الربح في السنة الأولى ( {{ $calc_total['first_year_net_cash_flow'] }}) وفي السنة الثانية ( {{ $calc_total['second_year_net_cash_flow'] }}) وفي السنة الثالثة ( {{ $calc_total['third_year_net_cash_flow'] }}) .</p>
                        <p>***وبالنسبة للتغير في رأس المال العامل فقيمتة في السنة الأولى ( {{ $calc_total['first_year_capital_change']}}) و في السنة الثانية ({{ $calc_total['second_year_capital_change']}}) وفي السنة الثالثة ({{ $calc_total['third_year_capital_change']}})</p>
                        <p>***وبالنسبة لصافي التدفق النقدي من الأنشطة التشغيلية  في السنة الأولى ف ({{$calc_total['second_year_net_cash_flow']}}) وفي السنة الثانية ({{$calc_total['third_year_net_cash_flow']}}) وفي السنة الثالثة ({{$calc_total['first_year_net_cash_flow']}})</p>
                        <p> ***و نموذج الاستثمار الرأسمالي ف إجمالي الإيرادات في السنة الأولى فقيمتها ( {{ $calc_total['totalRevenueFirstYear'] }}) وفي السنة الثانية فقيمتها ( {{ $calc_total['totalRevenueSecondYear'] }}) وفي السنة الثالثة فقيمتها ( {{ $calc_total['totalRevenueThirdYear']  }})</p>
                        <p>***و إجمالي التكاليف قيمته في السنة الأولى ( {{$calc_total['first_year_costs'] }}) وفي السنة الثانية ( {{ $calc_total['second_year_costs'] }}) وفي السنة الثالثة فقيمته ( {{ $calc_total['third_year_costs']}})</p>
                        <p>***و صافي الربح قيمته في السنة الأولى ( {{ $calc_total['first_year_profit_after_zakat'] }}) وفي السنة الثانية ({{ $calc_total['second_year_profit_after_zakat'] }}) وفي السنة الثالثة فقيمته ({{ $calc_total['third_year_profit_after_zakat']}})</p>
                        <p>***اما بالنسبة لرأس مال المستثمر في فترة التأسيس ف قيمته (SAR {{ $totalInvestedCapital }})</p>
                        <p>***وبذلك يكون التدفق النقدي السنوي في السنة الأولى ( {{ $calc_total['first_year_net_cash_flow'] }}) وفي السنة الثانية ( {{ $calc_total['second_year_net_cash_flow'] }}) وفي السنة الثالثة ( {{ $calc_total['third_year_net_cash_flow'] }})</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')

@endsection

