
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
                                <td>
                                    @if($calc_total['net_profit_first_year_as_number'] < 0)
                                        <strong class="text-danger">{!! $calc_total['net_profit_first_year_as_string'] !!}</strong>
                                    @else
                                        {!! $calc_total['net_profit_first_year_as_string'] !!}
                                    @endif
                                </td>
                                <td>{{ $calc_total['net_profit_second_year_as_string']  }}</td>
                                <td>{{ $calc_total['net_profit_third_year_as_string']  }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: center">التغير في رأس المال العامل</td>

                                    @if($calc_total['net_profit_first_year_as_number'] < 0)
                                    <td class="bg-danger">
                                    </td>
                                        @else
                                          <td>
                                              {!! $calc_total['first_year_capital_change'] !!}
                                          </td>
                                     @endif

                                <td>{!! $calc_total['second_year_capital_change'] !!}</td>
                                <td>{!! $calc_total['third_year_capital_change'] !!}</td>
                            </tr>
                            <tr>
                                <td style="text-align: center">صافي التدفق النقدي من الأنشطة التشغيلية</td>
                                @if($calc_total['net_profit_first_year_as_number'] < 0)
                                    <td class="bg-danger">
                                    </td>
                                @else
                                <td>
                                    {!! $first_year_net_cash_flow!!}
                                </td>
                                @endif
                                <td @if($calc_total['pure_second_year_profit_after_zakat'] < 0) style="color: red;" @endif>{{ $second_year_net_cash_flow }}</td>
                                <td @if($calc_total['pure_third_year_profit_after_zakat'] < 0 ) style="color: red;" @endif>{{ $third_year_net_cash_flow }}</td>
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
