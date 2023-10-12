
@extends('layouts.primary')
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card card-body mb-4">
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="row">
                        <p>{{ __('statement_of_cash_flows') }}</p>
                    </div>
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('first_year')}}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('second_year')}}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('third_year')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $first_year_profit = $calc_total['first_year_profit_after_zakat'];
                                $second_year_profit = $calc_total['second_year_profit_after_zakat'];
                                $third_year_profit = $calc_total['third_year_profit_after_zakat'];
                                $first_year_net_cash_flow = $calc_total['first_year_net_cash_flow'];
                                $second_year_net_cash_flow = $calc_total['second_year_net_cash_flow'];
                                $third_year_net_cash_flow = $calc_total['third_year_net_cash_flow'];
                            @endphp
                            <tr>
                                <td style="text-align: center">صافي الربح</td>
                                <td>{{ $first_year_profit }}</td>
                                <td>{{ $second_year_profit }}</td>
                                <td>{{ $third_year_profit }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: center">التغير في رأس المال العامل</td>
                                <td>{{ $calc_total['first_year_capital_change'] }}</td>
                                <td>{{ $calc_total['second_year_capital_change'] }}</td>
                                <td>{{ $calc_total['third_year_capital_change'] }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: center">صافي التدفق النقدي من الأنشطة التشغيلية</td>
                                <td @if($calc_total['pure_first_year_profit_after_zakat'] < \App\Models\ProjectRevenuePlanning::calcTotalRevenueFirstYear()) style="color: red;" @endif>{{ $first_year_net_cash_flow }}</td>
                                <td @if($calc_total['pure_second_year_profit_after_zakat'] < \App\Models\ProjectRevenuePlanning::calcTotalRevenueSecondYear()) style="color: red;" @endif>{{ $second_year_net_cash_flow }}</td>
                                <td @if($calc_total['pure_third_year_profit_after_zakat'] < \App\Models\ProjectRevenuePlanning::calcTotalRevenueThirdYear()) style="color: red;" @endif>{{ $third_year_net_cash_flow }}</td>
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

@endsection
