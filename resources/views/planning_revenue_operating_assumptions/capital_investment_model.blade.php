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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{ __('first_year') }}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{ __('second_year') }}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{ __('third_year') }}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{ __('foundation_period') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align: center">اجمالي الايرادات</td>
                                    <td>{{ $calc_total['first_year_after_operating_assumption_as_string'] }}</td>
                                    <td>{{ $calc_total['second_year_after_operating_assumption_as_string'] }}</td>
                                    <td>{{ $calc_total['third_year_after_operating_assumption_as_string'] }}</td>
                                    {{--                                <td>{{ $totalInvestedCapital }}</td> --}}
                                    <td></td>
                                </tr>
                                <tr>
                                    <td style="text-align: center">اجمالي التكاليف</td>
                                    <td>

                                        {{ formatCurrency($calc_total['total_cost_first_year_as_number'] + $calc_total['first_year_profit_before_zakat_percent_number'], $currency) }}
                                    </td>
                                    <td>{{ formatCurrency($calc_total['total_cost_second_year_as_number'] + $calc_total['second_year_profit_before_zakat_percent_number'], $currency) }}
                                    </td>
                                    <td>{{ formatCurrency($calc_total['total_cost_third_year_as_number'] + $calc_total['third_year_profit_before_zakat_percent_number'], $currency) }}
                                    </td>
                                    {{--                                <td>{{ $totalInvestedCapital }}</td> --}}
                                    <td></td>
                                </tr>
                                <tr>
                                    <td style="text-align: center">صافي الربح</td>
                                    @if ($calc_total['net_profit_first_year_as_number'] < 0)
                                        <td class="text-danger">
                                            {{ $calc_total['net_profit_first_year_as_string'] }}
                                        </td>
                                    @else
                                        <td>
                                            {{ $calc_total['net_profit_first_year_as_string'] }}
                                        </td>
                                    @endif

                                    <td>{{ $calc_total['second_year_profit_after_zakat'] }}</td>
                                    <td>{{ $calc_total['third_year_profit_after_zakat'] }}</td>
                                    {{--                                <td>{{ $totalInvestedCapital }}</td> --}}
                                    <td></td>
                                </tr>
                                <tr>
                                    @php
                                        $first_year_profit = $calc_total['first_year_profit_after_zakat'];
                                        $second_year_profit = $calc_total['second_year_profit_after_zakat'];
                                        $third_year_profit = $calc_total['third_year_profit_after_zakat'];
                                        $first_year_net_cash_flow = $calc_total['first_year_net_cash_flow'];
                                        $second_year_net_cash_flow = $calc_total['second_year_net_cash_flow'];
                                        $third_year_net_cash_flow = $calc_total['third_year_net_cash_flow'];
                                    @endphp
                                    <td style="text-align: center">التدفق النقدي السنوي</td>
                                    @if ($calc_total['net_profit_first_year_as_number'] < 0)
                                        <td class="bg-danger">

                                        </td>
                                    @else
                                        <td>
                                            {!! $first_year_net_cash_flow !!}
                                        </td>
                                    @endif
                                    <td @if ($calc_total['pure_second_year_profit_after_zakat'] < 0) style="color: red;" @endif>
                                        {{ $second_year_net_cash_flow }}</td>
                                    <td @if ($calc_total['pure_third_year_profit_after_zakat'] < 0) style="color: red;" @endif>
                                        {{ $third_year_net_cash_flow }}</td>
                                    {{--                                <td>{{ $totalInvestedCapital }}</td> --}}
                                    <td></td>
                                </tr>
                                <tr>
                                    <td style="text-align: center">{{ __('Invested_capital') }}</td>
                                    {{--                                <td>{{ $totalInvestedCapital }}</td> --}}
                                    {{--                                <td>{{ $totalInvestedCapital }}</td> --}}
                                    {{--                                <td>{{ $totalInvestedCapital }}</td> --}}
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
