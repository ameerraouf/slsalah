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
                        <p> **وسيكون هناك مصروفات إدارية وعمومية في السنة الأولى بقيمة ({{ $all_revenues_forecasting['first_year'] * ($planningCostAssumption->general_expenses / 100) }} SAR) والتي تمثل {{ $planningCostAssumption->general_expenses }}% من إجمالي الإيرادات وأيضا في السنة الثانية بقيمة ({{ $all_revenues_forecasting['third_year'] * ($planningCostAssumption->general_expenses / 100) }} SAR) وأيضا في السنة الثالثة بقيمة ({{ $all_revenues_forecasting['second_year'] * ($planningCostAssumption->general_expenses / 100) }} SAR)</p>
                        <p> **وسيكون هناك مصروفات تشغيلية في السنة الأولى بقيمة ({{ $all_revenues_forecasting['first_year'] * ($planningCostAssumption->operational_costs / 100) }} SAR) والتي تمثل {{ $planningCostAssumption->operational_costs }}% من إجمالي الإيرادات وأيضا في السنة الثانية بقيمة ({{ $all_revenues_forecasting['third_year'] * ($planningCostAssumption->operational_costs / 100) }} SAR) وأيضا في السنة الثالثة بقيمة ({{ $all_revenues_forecasting['second_year'] * ($planningCostAssumption->operational_costs / 100) }} SAR)</p>
                        <p>***وسيكون هناك مصروفات تسويقية في السنة الأولى بقيمة ({{ $all_revenues_forecasting['first_year'] * ($planningCostAssumption->marketing_expenses / 100) }} SAR) والتي تمثل {{ $planningCostAssumption->marketing_expenses }}% من إجمالي الإيرادات وأيضا في السنة الثانية بقيمة ({{ $all_revenues_forecasting['third_year'] * ($planningCostAssumption->marketing_expenses / 100) }} SAR) وأيضا في السنة الثالثة بقيمة ({{ $all_revenues_forecasting['second_year'] * ($planningCostAssumption->marketing_expenses / 100) }} SAR)</p>
                        <p>وبالتالي فإن إجمالي التكاليف في السنة الأولى سيكون (SAR {{ $totalCostFirstYear }}) وفي السنة الثانية سيكون (SAR {{ $totalCostSecondYear }}) وفي السنة الثالثة سيكون (SAR {{ $totalCostThirdYear }})</p>
                        <p>***بالنسبة للربح قبل الزكاة EBIT  سيكون في السنة الأولى (SAR 37,125) وفي السنة الثانية (SAR 163,350) وفي السنة الثالثة  (SAR 179,685).</p>
                        <p>***وقيمة الزكاة ستكون (SAR 0) في حالة عدم وجود ربح في السنة الأولى وستكون (SAR 4,084) في السنة الثانية و ستكون (SAR 4,492) في السنة الثالثة .</p>


                        <p>***بالنسبة لصافي الربح  EAT فستكون قيمتة في السنة الأولى (SAR {{ $first_year_profit - ($first_year_profit*0.025) }}) وفي السنة الثانية (SAR {{ $second_year_profit - ($second_year_profit*0.025) }}) وفي السنة الثالثة (SAR {{ $third_year_profit - ($third_year_profit*0.025) }}) .</p>
                        <p>***بالنسبة لقائمة التدفقات النقدية من الأنشطة التشغيلية فصافي الربح في السنة الأولى (SAR {{ $first_year_net_cash_flow }}) وفي السنة الثانية (SAR {{ $second_year_net_cash_flow }}) وفي السنة الثالثة (SAR {{ $third_year_net_cash_flow }}) .</p>
                        <p>***وبالنسبة للتغير في رأس المال العامل فقيمتة في السنة الأولى (SAR {{ ($first_year_profit - ($first_year_profit*0.025)) - $first_year_net_cash_flow}}) و في السنة الثانية (SAR {{ ($second_year_profit - ($second_year_profit*0.025)) - $second_year_net_cash_flow}}) وفي السنة الثالثة (SAR {{ ($third_year_profit - ($third_year_profit*0.025)) - $third_year_net_cash_flow}})</p>
{{--                        <p>***وبالنسبة لصافي التدفق النقدي من الأنشطة التشغيلية  في السنة الأولى ف (SAR 0) وفي السنة الثانية (SAR 127,413) وفي السنة الثالثة (SAR 140,154)</p>--}}
                        <p> ***و نموذج الاستثمار الرأسمالي ف إجمالي الإيرادات في السنة الأولى فقيمتها (SAR {{ $all_revenues_forecasting['first_year'] }}) وفي السنة الثانية فقيمتها (SAR {{ $all_revenues_forecasting['second_year']}}) وفي السنة الثالثة فقيمتها (SAR {{ $all_revenues_forecasting['third_year'] }})</p>
                        <p>***و إجمالي التكاليف قيمته في السنة الأولى (SAR {{ $all_revenues_costs_forecasting['first_year'] + $first_year_zakat }}) وفي السنة الثانية (SAR {{ $all_revenues_costs_forecasting['second_year'] + $second_year_zakat }}) وفي السنة الثالثة فقيمته (SAR {{ $all_revenues_costs_forecasting['third_year'] + $third_year_zakat}})</p>
                        <p>***و صافي الربح قيمته في السنة الأولى (SAR {{ $first_year_profit_margin - $first_year_zakat }}) وفي السنة الثانية (SAR {{ $second_year_profit_margin - $second_year_zakat }}) وفي السنة الثالثة فقيمته (SAR {{ $third_year_profit_margin - $third_year_zakat}})</p>
                        <p>***اما بالنسبة لرأس مال المستثمر في فترة التأسيس ف قيمته (SAR {{ $totalInvestedCapital }})</p>
                        <p>***وبذلك يكون التدفق النقدي السنوي في السنة الأولى (SAR {{ $totalInvestedCapital }}) وفي السنة الثانية (SAR {{ $totalInvestedCapital }}) وفي السنة الثالثة (SAR {{ $totalInvestedCapital }})</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')

@endsection

