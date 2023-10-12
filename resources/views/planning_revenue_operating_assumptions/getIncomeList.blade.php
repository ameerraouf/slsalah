@extends('layouts.primary')
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card card-body mb-4">
                <div class="card-body px-0 pt-0 pb-2">

                    <div class="row mb-4z">
                        <div class="col-md-12" style="text-align: center">{{ __('total revenues') }}</div>
                        <div class="col-md-2" style="background-color: #DDD;">{{ __('first_year') }}</div>
                        <div class="col-md-2 text-muted">{{ formatCurrency(\App\Models\ProjectRevenuePlanning::calcTotalRevenueFirstYear() ,getWorkspaceCurrency($settings))}}</div>
                        <div class="col-md-2" style="background-color: #DDD;">{{ __('second_year') }}</div>
                        <div class="col-md-2 text-muted">{{ formatCurrency(\App\Models\ProjectRevenuePlanning::calcTotalRevenueSecondYear(),getWorkspaceCurrency($settings)) }}</div>
                        <div class="col-md-2" style="background-color: #DDD;">{{ __('third_year') }}</div>
                        <div class="col-md-2 text-muted">{{ formatCurrency(\App\Models\ProjectRevenuePlanning::calcTotalRevenueThirdYear() ,getWorkspaceCurrency($settings))}}</div>

                        {{--                                <td>{{ $total_first_year }} SAR</td>--}}
                        {{--                                <td>{{ $total_second_year }} SAR</td>--}}
                        {{--                                <td>{{ $total_third_year }} SAR</td>--}}
                    </div>
                    <div class="row">
                        <p>قائمة الدخل</p>
                    </div>
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('costs')}}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('first_year')}}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('second_year')}}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('third_year')}}</th>
                            </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td style="text-align: center">مصروفات تشغيلية</td>
                                    <td class="first_year">{{ formatCurrency((\App\Models\ProjectRevenuePlanning::calcTotalRevenueFirstYear() * $planningCostAssumption->operational_costs / 100) ,getWorkspaceCurrency($settings)) }}</td>
                                    <td class="second_year">{{ formatCurrency((\App\Models\ProjectRevenuePlanning::calcTotalRevenueSecondYear() * $planningCostAssumption->operational_costs / 100) ,getWorkspaceCurrency($settings)) }}</td>
                                    <td class="third_year">{{ formatCurrency((\App\Models\ProjectRevenuePlanning::calcTotalRevenueThirdYear() * $planningCostAssumption->operational_costs / 100) ,getWorkspaceCurrency($settings)) }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center">مصروفات عمومية</td>
                                    <td class="first_year">{{ formatCurrency((\App\Models\ProjectRevenuePlanning::calcTotalRevenueFirstYear() * $planningCostAssumption->general_expenses / 100),getWorkspaceCurrency($settings)) }}</td>
                                    <td class="second_year">{{ formatCurrency((\App\Models\ProjectRevenuePlanning::calcTotalRevenueSecondYear() * $planningCostAssumption->general_expenses / 100),getWorkspaceCurrency($settings)) }}</td>
                                    <td class="third_year">{{ formatCurrency((\App\Models\ProjectRevenuePlanning::calcTotalRevenueThirdYear() * $planningCostAssumption->general_expenses / 100),getWorkspaceCurrency($settings)) }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center">مصروفات تسويقية</td>
                                    <td class="first_year">{{ formatCurrency((\App\Models\ProjectRevenuePlanning::calcTotalRevenueFirstYear() * $planningCostAssumption->marketing_expenses / 100) ,getWorkspaceCurrency($settings)) }}</td>
                                    <td class="second_year">{{ formatCurrency((\App\Models\ProjectRevenuePlanning::calcTotalRevenueSecondYear() * $planningCostAssumption->marketing_expenses / 100) ,getWorkspaceCurrency($settings)) }}</td>
                                    <td class="third_year">{{ formatCurrency((\App\Models\ProjectRevenuePlanning::calcTotalRevenueThirdYear() * $planningCostAssumption->marketing_expenses / 100) ,getWorkspaceCurrency($settings)) }}</td>
                                </tr>
                                <tr style="border-bottom: 5px;">
                                    <td style="text-align: center;">{{__('total')}}</td>
                                    <td id="first_year_total"></td>
                                    <td id="second_year_total"></td>
                                    <td id="third_year_total"></td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">{{__('yearly_profit_before_zakat')}}</td>
                                    <td>{{ $calc_total['first_year_profit_before_zakat'] }}</td>
                                    <td>{{ $calc_total['second_year_profit_before_zakat'] }}</td>
                                    <td>{{ $calc_total['third_year_profit_before_zakat'] }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">{{__('profit_before_zakat')}}</td>
                                    <td style="text-align: center;" id="profit_before_zakat"></td>
                                    <td style="text-align: center;">{{__('zakat_percent_value')}}</td>
                                    <td style="text-align: center;" id="zakat_percent_value">2.5%</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;" colspan="2">{{__('profit_after_zakat')}}</td>
                                    <td style="text-align: center;" colspan="2" id="profit_after_zakat"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')

    <script>
        $(document).ready(function(){
            $('#first_year_total').text('<?=$calc_total['first_year']?>');
            $('#second_year_total').text('<?=$calc_total['second_year']?>');
            $('#third_year_total').text('<?=$calc_total['third_year']?>');
            $('#profit_before_zakat').text('<?=$calc_total['profit_before_zakat']?>');
            {{--$('#zakat_percent_value').text('<?=$calc_total['zakat_percent_value']?>');--}}
            $('#profit_after_zakat').text('<?=$calc_total['profit_after_zakat']?>');
        });
    </script>
@endsection

