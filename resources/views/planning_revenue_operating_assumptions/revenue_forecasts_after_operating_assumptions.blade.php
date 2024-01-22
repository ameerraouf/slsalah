@extends('layouts.primary')
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card card-body mb-4">
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="row">
                        <p>توقعات الايرادات بعد افتراضات التشغيل</p>

                    </div>
                    <div class="table-responsive p-0">
                        @foreach($projectRevenuesPlanning as $projectRevenuePlanning)
                            @php
                                $total_first_year = 0;
                                $total_second_year = 0;
                                $total_third_year = 0;
                            @endphp

                            <div style="text-align: center;" class="fa-2x mt-4"><span class=" px-2 ">{{ $projectRevenuePlanning->name }}</span></div>

                            <table class="table align-items-center mb-0">
                                <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('revenue_name')}}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('source_unit')}}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('source_unit_price')}}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('first_year')}}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('second_year')}}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('third_year')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($projectRevenuePlanning->sources as $source)
                                    @php
                                        /*
                                            $total_first_year += ($source->total_revenue * ($planningRevenueOperatingAssumptions->first_year/100));
                                            $total_second_year += (($source->total_revenue * ($planningRevenueOperatingAssumptions->first_year/100)) + ($source->total_revenue * ($planningRevenueOperatingAssumptions->second_year/100)));
                                            $total_third_year += (($source->total_revenue * ($planningRevenueOperatingAssumptions->first_year/100)) + ($source->total_revenue * ($planningRevenueOperatingAssumptions->second_year/100)) + ((($source->total_revenue * ($planningRevenueOperatingAssumptions->first_year/100)) + ($source->total_revenue * ($planningRevenueOperatingAssumptions->second_year/100))) * ($planningRevenueOperatingAssumptions->third_year/100)));
                                        */
                                    @endphp


                                    <tr>
                                        <td>{{ $source->name }}</td>
                                        <td>{{ $source->unit }}</td>
                                        <td>{{ $source->unit_price }} SAR</td>
                                        <td>
                                            @if($planningRevenueOperatingAssumptions->first_year != 100)
                                                {{ formatCurrency(($source->total_revenue * ($planningRevenueOperatingAssumptions->first_year / 100)),$currency) }}
                                            @else
                                                {{ formatCurrency($source->total_revenue,$currency) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($planningRevenueOperatingAssumptions->second_year != 100)
                                                {{ formatCurrency(($source->total_second_revenue * ($planningRevenueOperatingAssumptions->second_year / 100)),$currency) }}
                                            @else
                                                {{ formatCurrency($source->total_second_revenue,$currency) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($planningRevenueOperatingAssumptions->third_year != 100)
                                                {{ formatCurrency(($source->total_third_revenue * ($planningRevenueOperatingAssumptions->third_year / 100)),$currency) }}
                                            @else
                                                {{ formatCurrency($source->total_third_revenue,$currency) }}
                                            @endif
                                        </td>

{{--                                        <td>{{ $source->total_revenue * ($planningRevenueOperatingAssumptions->first_year/100) }} SAR</td>--}}
{{--                                        <td>{{ ($source->total_revenue * ($planningRevenueOperatingAssumptions->first_year/100)) + ($source->total_revenue * ($planningRevenueOperatingAssumptions->second_year/100)) }} SAR</td>--}}
{{--                                        <td>{{ ($source->total_revenue * ($planningRevenueOperatingAssumptions->first_year/100)) + ($source->total_revenue * ($planningRevenueOperatingAssumptions->second_year/100)) + ((($source->total_revenue * ($planningRevenueOperatingAssumptions->first_year/100)) + ($source->total_revenue * ($planningRevenueOperatingAssumptions->second_year/100))) * ($planningRevenueOperatingAssumptions->third_year/100)) }} SAR</td>--}}
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                        @endforeach
                    </div>

                    <div class="row mt-4" >
                        <div class="col-md-12 my-2" style="text-align: center">{{ __('total') }}</div>
                        <div class="col-md-2" style="background-color: #DDD;">{{ __('first_year') }}</div>
                        @php
                            $planningRevenueOperatingAssumptions =\App\Models\PlanningRevenueOperatingAssumption::query()->where('workspace_id', auth()->user()->workspace_id)->first();
                            $first_year_percentage =  $planningRevenueOperatingAssumptions? $planningRevenueOperatingAssumptions->first_year / 100: .50;
                            $second_year_percentage = $planningRevenueOperatingAssumptions? $planningRevenueOperatingAssumptions->second_year / 100: 1;
                             $third_year_percentage = $planningRevenueOperatingAssumptions? $planningRevenueOperatingAssumptions->third_year / 100: 1;
                        @endphp
                        <div class="col-md-2 text-muted">{{ formatCurrency(\App\Models\ProjectRevenuePlanning::calcTotalRevenueFirstYear() * $first_year_percentage,$currency )}}</div>
                        <div class="col-md-2" style="background-color: #DDD;">{{ __('second_year') }}</div>
                        <div class="col-md-2 text-muted">{{ formatCurrency(\App\Models\ProjectRevenuePlanning::calcTotalRevenueSecondYear() * $second_year_percentage,$currency) }}</div>
                        <div class="col-md-2" style="background-color: #DDD;">{{ __('third_year') }}</div>
                        <div class="col-md-2 text-muted">{{ formatCurrency(\App\Models\ProjectRevenuePlanning::calcTotalRevenueThirdYear()  * $third_year_percentage,$currency)}}</div>

                        {{--                                <td>{{ $total_first_year }} SAR</td>--}}
                        {{--                                <td>{{ $total_second_year }} SAR</td>--}}
                        {{--                                <td>{{ $total_third_year }} SAR</td>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

