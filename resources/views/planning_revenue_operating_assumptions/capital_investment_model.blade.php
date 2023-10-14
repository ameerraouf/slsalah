

@extends('layouts.primary')
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card card-body mb-4">
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="row">
                        <p>{{ __('capital_investment_model') }}</p>
                    </div>
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('first_year')}}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('second_year')}}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('third_year')}}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('foundation_period')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td style="text-align: center">اجمالي الايرادات</td>
                                <td>{{ $calc_total['totalRevenueFirstYear'] }}</td>
                                <td>{{ $calc_total['totalRevenueSecondYear']}}</td>
                                <td>{{ $calc_total['totalRevenueThirdYear'] }}</td>
{{--                                <td>{{ $totalInvestedCapital }}</td>--}}
                                <td></td>
                            </tr>
                            <tr>
                                <td style="text-align: center">اجمالي التكاليف</td>
                                <td>{{ $calc_total['first_year_costs'] }}</td>
                                <td>{{ $calc_total['second_year_costs'] }}</td>
                                <td>{{ $calc_total['third_year_costs'] }}</td>
{{--                                <td>{{ $totalInvestedCapital }}</td>--}}
                                <td></td>
                            </tr>
                            <tr>
                                <td style="text-align: center">صافي الربح</td>
                                <td>{{ $calc_total['first_year_profit_after_zakat'] }}</td>
                                <td>{{ $calc_total['second_year_profit_after_zakat'] }}</td>
                                <td>{{ $calc_total['third_year_profit_after_zakat'] }}</td>
{{--                                <td>{{ $totalInvestedCapital }}</td>--}}
                                <td></td>
                            </tr>
                            <tr>
                                <td style="text-align: center">التدفق النقدي السنوي</td>
                                <td @if($calc_total['first_year_net_cash_flow_number'] < 0) style="color: red;" @endif>{{ $calc_total['first_year_net_cash_flow'] }}</td>
                                <td @if($calc_total['third_year_net_cash_flow_number'] < 0) style="color: red;" @endif>{{ $calc_total['third_year_net_cash_flow'] }}</td>
                                <td @if($calc_total['third_year_net_cash_flow_number'] < 0) style="color: red;" @endif>{{ $calc_total['third_year_net_cash_flow'] }}</td>
{{--                                <td>{{ $totalInvestedCapital }}</td>--}}
                                <td></td>
                            </tr>
                            <tr>
                                <td style="text-align: center">{{ __('Invested_capital') }}</td>
{{--                                <td>{{ $totalInvestedCapital }}</td>--}}
{{--                                <td>{{ $totalInvestedCapital }}</td>--}}
{{--                                <td>{{ $totalInvestedCapital }}</td>--}}
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{ $totalInvestedCapital }}</td>
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