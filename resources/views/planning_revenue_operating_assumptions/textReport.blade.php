@extends('layouts.primary')
{{-- @section('head')
    
@endsection --}}
@if ($calc_total)
    @section('content')
        <div class="row">
            <div class="col-12 mb-3 d-flex justify-content-end">
                <button class="btn btn-primary" id="print-pdf-btn">تصدير PDF / طباعة</button>
            </div>
            <div class="col-12">
                <div class="card card-body mb-4">
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="row">
                            <p> التقارير النصية</p>
                        </div>
                        <div class="table-responsive p-0">
                            @php
                                $revenues = '';
                                foreach ($projectRevenues as $revenue) {
                                    $revenues .= $revenue->name . ' و ';
                                }
                                $planningRevenueOperatingAssumptions = \App\Models\PlanningRevenueOperatingAssumption::query()
                                    ->where('workspace_id', auth()->user()->workspace_id)
                                    ->first();
                                $first_year_percentage = $planningRevenueOperatingAssumptions ? $planningRevenueOperatingAssumptions->first_year / 100 : 0.5;
                                $second_year_percentage = $planningRevenueOperatingAssumptions ? $planningRevenueOperatingAssumptions->second_year / 100 : 1;
                                $third_year_percentage = $planningRevenueOperatingAssumptions ? $planningRevenueOperatingAssumptions->third_year / 100 : 1;

                                $totalCostFirstYear = $all_revenues_forecasting['first_year'] * ($planningCostAssumption->general_expenses / 100) + $all_revenues_forecasting['first_year'] * ($planningCostAssumption->operational_costs / 100) + $all_revenues_forecasting['first_year'] * ($planningCostAssumption->marketing_expenses / 100);
                                $totalCostSecondYear = $all_revenues_forecasting['second_year'] * ($planningCostAssumption->general_expenses / 100) + $all_revenues_forecasting['second_year'] * ($planningCostAssumption->operational_costs / 100) + $all_revenues_forecasting['second_year'] * ($planningCostAssumption->marketing_expenses / 100);
                                $totalCostThirdYear = $all_revenues_forecasting['third_year'] * ($planningCostAssumption->general_expenses / 100) + $all_revenues_forecasting['third_year'] * ($planningCostAssumption->operational_costs / 100) + $all_revenues_forecasting['third_year'] * ($planningCostAssumption->marketing_expenses / 100);
                                $first_year_profit = $all_revenues_forecasting['first_year'] - $all_revenues_costs_forecasting['first_year'];
                                $second_year_profit = $all_revenues_forecasting['second_year'] - $all_revenues_costs_forecasting['second_year'];
                                $third_year_profit = $all_revenues_forecasting['third_year'] - $all_revenues_costs_forecasting['third_year'];
                                $first_year_net_cash_flow = ($first_year_profit - $first_year_profit * 0.025) * ($planningFinancialAssumption->cash_percentage_of_net_profit / 100);
                                $second_year_net_cash_flow = ($second_year_profit - $second_year_profit * 0.025) * ($planningFinancialAssumption->cash_percentage_of_net_profit / 100);
                                $third_year_net_cash_flow = ($third_year_profit - $third_year_profit * 0.025) * ($planningFinancialAssumption->cash_percentage_of_net_profit / 100);
                                $first_year_profit_margin = $all_revenues_forecasting['first_year'] - $all_revenues_costs_forecasting['first_year'];
                                $second_year_profit_margin = $all_revenues_forecasting['second_year'] - $all_revenues_costs_forecasting['second_year'];
                                $third_year_profit_margin = $all_revenues_forecasting['third_year'] - $all_revenues_costs_forecasting['third_year'];
                                $first_year_zakat = $first_year_profit_margin * 0.025;
                                $second_year_zakat = $second_year_profit_margin * 0.025;
                                $third_year_zakat = $third_year_profit_margin * 0.025;

                            @endphp
                            @php
                                $first_year_profit = $calc_total['first_year_profit_after_zakat'];
                                $second_year_profit = $calc_total['second_year_profit_after_zakat'];
                                $third_year_profit = $calc_total['third_year_profit_after_zakat'];
                                $first_year_net_cash_flow = $calc_total['first_year_net_cash_flow'];
                                $second_year_net_cash_flow = $calc_total['second_year_net_cash_flow'];
                                $third_year_net_cash_flow = $calc_total['third_year_net_cash_flow'];
                                $firstYearProfit = $calc_total['first_year_profit_before_zakat'] > 0 ? ' وقيمة الزكاه ستكون ( 0 SAR )في السنة الاولي في حالة عدم وجود ربح' : 'وقيمة الزكاة ستكون في السنة الاولي ( ' . $calc_total['zakat_first_year_value_as_string'] . ')';

                            @endphp
                            <p>*يمكن تحقيق بواسطة {{ $revenues }} سيحقق في السنة الأولى
                                (({{ formatCurrency(\App\Models\ProjectRevenuePlanning::calcTotalRevenueFirstYear() * $first_year_percentage, getWorkspaceCurrency($settings)) }}
                                وسيحقق في السنة الثانية
                                ({{ formatCurrency(\App\Models\ProjectRevenuePlanning::calcTotalRevenueSecondYear() * $second_year_percentage, getWorkspaceCurrency($settings)) }})
                                وسيحقق في السنة الثالثة
                                ({{ formatCurrency(\App\Models\ProjectRevenuePlanning::calcTotalRevenueThirdYear() * $third_year_percentage, getWorkspaceCurrency($settings)) }})
                            </p>
                            <p> **وسيكون هناك مصروفات إدارية وعمومية في السنة الأولى بقيمة
                                ({{ $calc_total['first_year_operating_general_as_string'] }} ) والتي تمثل
                                {{ $planningCostAssumption->general_expenses }}% من إجمالي الإيرادات وأيضا في السنة الثانية
                                بقيمة ({{ $calc_total['second_year_operating_general_as_string'] }}) وأيضا في السنة الثالثة
                                بقيمة ({{ $calc_total['third_year_operating_general_as_string'] }})</p>
                            <p> **وسيكون هناك مصروفات تشغيلية في السنة الأولى بقيمة
                                ({{ $calc_total['first_year_operating_expenses_as_string'] }}) والتي تمثل
                                {{ $planningCostAssumption->operational_costs }}% من إجمالي الإيرادات وأيضا في السنة الثانية
                                بقيمة ({{ $calc_total['second_year_operating_expenses_as_string'] }} ) وأيضا في السنة
                                الثالثة بقيمة ({{ $calc_total['third_year_operating_expenses_as_string'] }})</p>
                            <p>***وسيكون هناك مصروفات تسويقية في السنة الأولى بقيمة
                                ({{ $calc_total['first_year_operating_marketing_as_string'] }} ) والتي تمثل
                                {{ $planningCostAssumption->marketing_expenses }}% من إجمالي الإيرادات وأيضا في السنة
                                الثانية بقيمة ({{ $calc_total['second_year_operating_marketing_as_string'] }}) وأيضا في
                                السنة الثالثة بقيمة ({{ $calc_total['third_year_operating_marketing_as_string'] }})</p>
                            <p>وبالتالي فإن إجمالي التكاليف في السنة الأولى سيكون (
                                {{ formatCurrency($calc_total['total_cost_first_year_as_number'] + $calc_total['first_year_profit_before_zakat_percent_number'], getWorkspaceCurrency($settings)) }})
                                وفي السنة الثانية سيكون (
                                {{ formatCurrency($calc_total['total_cost_second_year_as_number'] + $calc_total['second_year_profit_before_zakat_percent_number'], getWorkspaceCurrency($settings)) }})
                                وفي السنة الثالثة سيكون (
                                {{ formatCurrency($calc_total['total_cost_third_year_as_number'] + $calc_total['third_year_profit_before_zakat_percent_number'], getWorkspaceCurrency($settings)) }})
                            </p>
                            <p>***بالنسبة للربح قبل الزكاة EBIT سيكون في السنة الأولى
                                ({{ $calc_total['first_year_profit_before_zakat'] }}) وفي السنة الثانية
                                ({{ $calc_total['second_year_profit_before_zakat'] }}) وفي السنة الثالثة
                                ({{ $calc_total['third_year_profit_before_zakat'] }}).</p>
                            <p>***
                                <span>{{ $firstYearProfit }}</span>
                                <span>وستكون ({{ $calc_total['zakat_second_year_value_as_string'] }} ) في السنة
                                    الثانية</span>
                                <span>وستكون ({{ $calc_total['zakat_third_year_value_as_string'] }} ) في السنة
                                    الثالثة</span>
                            </p>


                            <p>***بالنسبة لصافي الربح EAT فستكون قيمتة في السنة الأولى (
                                {{ $calc_total['net_profit_first_year_as_string'] }}) وفي السنة الثانية (
                                {{ $calc_total['net_profit_second_year_as_string'] }}) وفي السنة الثالثة (
                                {{ $calc_total['net_profit_third_year_as_string'] }}) .</p>
                            <p>***بالنسبة لقائمة التدفقات النقدية من الأنشطة التشغيلية فصافي الربح في السنة الأولى (
                                {{ $calc_total['net_profit_first_year_as_string'] }}) وفي السنة الثانية (
                                {{ $calc_total['net_profit_second_year_as_string'] }}) وفي السنة الثالثة (
                                {{ $calc_total['net_profit_third_year_as_string'] }}) .</p>
                            <p>***وبالنسبة للتغير في رأس المال العامل فقيمتة في السنة الأولى (
                                {{ $calc_total['net_profit_first_year_as_number'] < 0 ? 'SAR 0' : $calc_total['first_year_capital_change'] }})
                                و في السنة الثانية ({{ $calc_total['second_year_capital_change'] }}) وفي السنة الثالثة
                                ({{ $calc_total['third_year_capital_change'] }})</p>
                            <p>***وبالنسبة لصافي التدفق النقدي من الأنشطة التشغيلية في السنة الأولى ف
                                ({{ $first_year_net_cash_flow }}) وفي السنة الثانية ({{ $second_year_net_cash_flow }}) وفي
                                السنة الثالثة ({{ $third_year_net_cash_flow }})</p>
                            <p> ***و نموذج الاستثمار الرأسمالي ف إجمالي الإيرادات في السنة الأولى فقيمتها (
                                {{ $calc_total['first_year_after_operating_assumption_as_string'] }}) وفي السنة الثانية
                                فقيمتها ( {{ $calc_total['second_year_after_operating_assumption_as_string'] }}) وفي السنة
                                الثالثة فقيمتها ( {{ $calc_total['third_year_after_operating_assumption_as_string'] }})</p>
                            <p>***و إجمالي التكاليف قيمته في السنة الأولى (
                                {{ formatCurrency($calc_total['total_cost_first_year_as_number'] + $calc_total['first_year_profit_before_zakat_percent_number'], getWorkspaceCurrency($settings)) }})
                                وفي السنة الثانية (
                                {{ formatCurrency($calc_total['total_cost_second_year_as_number'] + $calc_total['second_year_profit_before_zakat_percent_number'], getWorkspaceCurrency($settings)) }})
                                وفي السنة الثالثة فقيمته (
                                {{ formatCurrency($calc_total['total_cost_third_year_as_number'] + $calc_total['third_year_profit_before_zakat_percent_number'], getWorkspaceCurrency($settings)) }})
                            </p>
                            <p>***و صافي الربح قيمته في السنة الأولى (
                                {{ $calc_total['net_profit_first_year_as_string'] }}) وفي السنة الثانية
                                ({{ $calc_total['net_profit_second_year_as_string'] }}) وفي السنة الثالثة فقيمته
                                ({{ $calc_total['net_profit_third_year_as_string'] }})</p>
                            <p>***اما بالنسبة لرأس مال المستثمر في فترة التأسيس ف قيمته (SAR {{ $totalInvestedCapital }})
                            </p>
                            <p>***وبذلك يكون التدفق النقدي السنوي في السنة الأولى ( {{ $first_year_net_cash_flow }}) وفي
                                السنة الثانية ( {{ $second_year_net_cash_flow }}) وفي السنة الثالثة (
                                {{ $third_year_net_cash_flow }})</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@else
    @section('content')
        <div class="row text-center container my-3 mx-auto">
            لاتوجد بيانات للعرض
        </div>
    @endsection
@endif

@section('script')

    <script>
        $('document').ready(function() {
            $('body').on('click', '#print-pdf-btn', function() {
                $(this).addClass('d-none')
                window.print()

                setTimeout(() => {
                $(this).removeClass('d-none')
                }, 200);
            })
        })
    </script>
@endsection
