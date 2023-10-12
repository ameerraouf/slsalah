@extends('layouts.primary')
@section('head')
    <style>
        .canvasjs-chart-container, .canvasjs-chart-canvas{
            /*position: initial !important;*/
        }
    </style>
@endsection
@section('content')



        <div class="row">
            <div class="col-md-7 mx-auto text-center">
                <h3 class="text-dark">{{__('خطتي : التقرير النهائي')}}</h3>
{{--                <p class="text-secondary">{{__('Choose the plan that best fit for you.')}}</p>--}}
                <button type="button" class="btn btn-success" id="generate_pdf"><i class="fas fa-file-pdf ms-2"></i>تصدير PDF</button>
            </div>
        </div>

        <div class="row mt-4" id="content" dir="rtl">
            <div class="card">
                <div class="card-body">
                    <h4>تخطيط إيرادات المشروع</h4>
                    <div class="row">
                        @foreach($projectRevenues as $revenue)
                            <div class="col-md-4">
                                <table class="table align-items-center m-1" border="1" id="cloudonex_table">
                                    <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ $revenue->name }}</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('source_unit')}}</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('source_unit_price')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($revenue->sources as $source)
                                            <tr>
                                                <td>{{ $source->name }}</td>
                                                <td>{{ $source->unit }}</td>
                                                <td>{{ $source->unit_price }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                    <div class="row mt-2">
                        <h4>{{__('revenue_forecast')}}</h4>
                        <table class="table align-items-center mb-0" id="cloudonex_table">
                            <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('first_year')}}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('second_year')}}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('third_year')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($projectRevenues) > 0)

                                @foreach($projectRevenues as $revenue)
                                    <tr>
                                        <th colspan="4">{{ $revenue->name }}
                                        </th>
                                    </tr>
                                    @foreach($revenue->sources as $source)
                                        <tr>
                                            <td> @if($revenue->main_unit ==0) {{ $source->name }} @endif</td>
                                            <td>{{ formatCurrency($source->total_revenue,getWorkspaceCurrency($settings)) }}</td>
                                            <td>{{ formatCurrency($source->total_second_revenue,getWorkspaceCurrency($settings)) }}</td>
                                            <td>{{ formatCurrency($source->total_third_revenue,getWorkspaceCurrency($settings)) }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                                <tr>
                                    <th>{{ __('total') }}</th>
                                    <th class="text-muted">{{ formatCurrency(\App\Models\ProjectRevenuePlanning::calcTotalRevenueFirstYear() ,getWorkspaceCurrency($settings))}}</th>
                                    <th class="text-muted">{{ formatCurrency(\App\Models\ProjectRevenuePlanning::calcTotalRevenueSecondYear(),getWorkspaceCurrency($settings)) }}</th>
                                    <th class="text-muted">{{ formatCurrency(\App\Models\ProjectRevenuePlanning::calcTotalRevenueThirdYear() ,getWorkspaceCurrency($settings))}}</th>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        @foreach($projectRevenues as $revenue)
                            <div class="col-md-6">
                                <div id="chartContainer_{{ $revenue->id }}" style="width: 100%; height: 300px;display: inline-block;" class="position-"></div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <h4>{{__('Tasks_chart')}}</h4>
                        <table class="table align-items-center m-1" border="1" id="cloudonex_table">
                            <thead>
                            <tr>
                                <th class="text-uppercase  text-xxs font-weight-bolder" colspan="5">{{ __('strategic_objective') }}</th>
                            </tr>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder " colspan="5">{{ $task_goal->description }}</th>
                            </tr>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder">{{ __('Subject/Task') }}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder">{{ __('Assigned To') }}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder">{{ __('Start Date') }}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder">{{ __('Due Date') }}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder">{{ __('Status') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td colspan="5"><p>{{ __('workplan') }}</p></td>
                            </tr>
                            @foreach($tasks as $task)
                                <tr>
                                    <td>{{ $task->subject }}</td>
                                    <td>{{ isset($task->assign) ?  $task->assign->first_name . ' ' . $task->assign->last_name : '' }}</td>
                                    <td>
                                        @if (!empty($task->start_date))
                                            {{ \App\Supports\DateSupport::parse($task->start_date)->format(config('app.date_time_format')) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if (!empty($task->due_date))
                                            {{ $task->due_date->format(config('app.date_time_format')) }}
                                        @endif
                                    </td>
                                    <td>
                                        <button
                                                class="text-xs btn btn-sm
                                         @if ($task->status === 'Not Started') btn-info
                                            @elseif($task->status === 'done')
                                                    btn-success
                                            @elseif($task->status === 'in_progress')
                                                    btn-primary
                                            @elseif($task->status === 'in_review')
                                                    btn-warning
                                            @else
                                                        btn-secondary @endif
                                                        ">
                                            {{ $task->status ?? 'todo' }}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <h4>{{__('fixed capital')}}</h4>
                        <div class="col-md-12">
                            <table class="table align-items-center m-1" border="1" id="cloudonex_table">
                                <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('Elements of fixed capital')}}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('total cost')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($fixedInvested as $invest)
                                    <tr>
                                        <td>{{ $invest->investing_description }}</td>
                                        <td>{{ $invest->investing_price }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <h5>مخطط رأس المال الثابت</h5>
                        <div class="col-md-12">
                            <div id="fixed_chartContainer" style="height: 370px; max-width: 920px; margin: 0px auto;"></div>
                        </div>
                    </div>

                    <div class="row">
                        <h4>{{__('عناصر رأس المال العامل')}}</h4>
                        <div class="col-md-12">
                            <table class="table align-items-center m-1" border="1" id="cloudonex_table">
                                <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('Elements of working capital')}}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('monthly cost')}}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('annual cost')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($workingInvested as $invest)
                                    <tr>
                                        <td>{{ $invest->investing_description }}</td>
                                        <td>{{ $invest->investing_monthly_cost }}</td>
                                        <td>{{ $invest->investing_annual_cost }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <h5>خطة رأس المال العامل </h5>
                        <div class="col-md-12">
                            <div id="working_chartContainer" style="height: 370px; max-width: 920px; margin: 0px auto;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <h4>{{__('افتراضات التكاليف ')}}</h4>
                        <div class="col-md-6">
                            <table class="table align-items-center mb-0" border="1">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-xxs font-weight-bolder">{{__('operational_costs')}}</th>
                                        <td class="text-uppercase text-secondary text-xxs font-weight-bolder">{{ $planningCostAssumption ? $planningCostAssumption->operational_costs : 0 }} %</td>
                                    </tr>
                                    <tr>
                                        <th class="text-uppercase text-xxs font-weight-bolder">{{__('general_expenses')}}</th>
                                        <td class="text-uppercase text-secondary text-xxs font-weight-bolder">{{ $planningCostAssumption ? $planningCostAssumption->general_expenses : 0 }} %</td>
                                    </tr>
                                    <tr>
                                        <th class="text-uppercase text-xxs font-weight-bolder">{{__('marketing_expenses')}}</th>
                                        <td class="text-uppercase text-secondary text-xxs font-weight-bolder">{{ $planningCostAssumption ? $planningCostAssumption->marketing_expenses : 0 }} %</td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        @foreach($projectRevenues as $revenue)
                            <div class="col-md-6">
                                <div id="costAssumption_chart_{{ $revenue->id }}" style="width: 100%; height: 300px;display: inline-block;" class="position-"></div>
                            </div>
                        @endforeach
                        <h4>{{__('قائمة الدخل')}}</h4>
                        <div class="col-md-12">
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
                                <tr>
                                    <td style="text-align: center;">{{__('total')}}</td>
                                    <td id="first_year_total"></td>
                                    <td id="second_year_total"></td>
                                    <td id="third_year_total"></td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">{{__('profit_before_zakat')}}</td>
                                    <td style="text-align: center;" id="profit_before_zakat"></td>
                                    <td style="text-align: center;">{{__('zakat_percent_value')}}</td>
                                    <td style="text-align: center;" id="zakat_percent_value"></td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;" colspan="2">{{__('profit_after_zakat')}}</td>
                                    <td style="text-align: center;" colspan="2" id="profit_after_zakat"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <h4>{{__('قائمة التدفقات النقدية من الأنشطة التشغيلية ')}}</h4>
                        <div class="col-md-12">
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
                        <h4>{{__('نموذج الاستثمار الرأسمالي')}}</h4>
                        <div class="col-md-12">
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
                                    <td>{{ $totalInvestedCapital }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center">اجمالي التكاليف</td>
                                    <td>{{ $calc_total['first_year_costs'] }}</td>
                                    <td>{{ $calc_total['second_year_costs'] }}</td>
                                    <td>{{ $calc_total['third_year_costs'] }}</td>
                                    <td>{{ $totalInvestedCapital }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center">صافي الربح</td>
                                    <td>{{ $calc_total['first_year_profit_after_zakat'] }}</td>
                                    <td>{{ $calc_total['second_year_profit_after_zakat'] }}</td>
                                    <td>{{ $calc_total['third_year_profit_after_zakat'] }}</td>
                                    <td>{{ $totalInvestedCapital }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center">التدفق النقدي السنوي</td>
                                    <td @if($calc_total['pure_first_year_profit_after_zakat'] < \App\Models\ProjectRevenuePlanning::calcTotalRevenueFirstYear()) style="color: red;" @endif>{{ $calc_total['first_year_cash_flow'] }}</td>
                                    <td @if($calc_total['pure_second_year_profit_after_zakat'] < \App\Models\ProjectRevenuePlanning::calcTotalRevenueSecondYear()) style="color: red;" @endif>{{ $calc_total['second_year_cash_flow'] }}</td>
                                    <td @if($calc_total['pure_third_year_profit_after_zakat'] < \App\Models\ProjectRevenuePlanning::calcTotalRevenueThirdYear()) style="color: red;" @endif>{{ $calc_total['third_year_cash_flow'] }}</td>
                                    <td>{{ $totalInvestedCapital }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center">{{ __('Invested_capital') }}</td>
                                    <td>{{ $totalInvestedCapital }}</td>
                                    <td>{{ $totalInvestedCapital }}</td>
                                    <td>{{ $totalInvestedCapital }}</td>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.min.js" integrity="sha512-d5Jr3NflEZmFDdFHZtxeJtBzk0eB+kkRXWFQqEc1EKmolXjHm2IKCA7kTvXBNjIYzjXfD5XzIjaaErpkZHCkBg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('js/canvasjs/canvasjs.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.debug.js"></script>
{{--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>--}}

    <script>
        charts = [];
        @foreach($projectRevenues as $revenue)
            chart =new CanvasJS.Chart("chartContainer_{{$revenue->id}}", {
                animationEnabled: true,
                title:{
                    text: "{!! $revenue->name !!}"
                },
                axisY: {
                    title: "",
                    includeZero: true,
                    suffix:  " SAR"
                },
                legend: {
                    cursor:"pointer",
                    itemclick : toggleDataSeries
                },
                toolTip: {
                    shared: true,
                    content: toolTipFormatter
                },
                data: [
                    {
                        type: "bar",
                        showInLegend: true,
                        name: "{!! __('source_unit') !!}",
                        color: "#ff8d04",
                        dataPoints: [
                            @foreach($revenue->sources as $source)
                                { y: {!! $source->unit !!}, label: '{!! $source->name !!}' },
                            @endforeach
                        ]
                    },
                    {
                        type: "bar",
                        showInLegend: true,
                        name: "{!! __('source_unit_price') !!}",
                        color: "#00ffeb",
                        dataPoints: [
                            @foreach($revenue->sources as $source)
                                { y: {!! $source->unit_price !!}, label: '{!! $source->name !!}' },
                            @endforeach
                        ]
                    },
                ]
            });
            chart.render();
        @endforeach

        CanvasJS.addColorSet("greenShades",
            [//colorSet Array
                "#90EE90",
                "#3CB371",
                "#bbc7de",
            ]);
        var chart = new CanvasJS.Chart("fixed_chartContainer", {
            animationEnabled: true,
            colorSet: "greenShades",
            title:{
                text: 'رأس المال الثابت هو قسم من رأس المال لا يتغير مهما تغير حجم الانتاج ويشمل رأس المال الثابت تكلفة شراء اى عناصر لازمه  لسير المشروع ولا تتغير قيمته بتغير حجم المشروع ',
                horizontalAlign: "center",
                fontSize: 20,
            },
            data: [{
                type: "doughnut",
                startAngle: 60,
                //innerRadius: 60,
                indexLabelFontSize: 17,
                indexLabel: "{label}",
                toolTipContent: "<p dir='rtl'><b>{label}</b> " + '</p>',
                dataPoints: {!! json_encode($fixedChart) !!}
            }]
        });
        chart.render();
        var chart = new CanvasJS.Chart("working_chartContainer", {
            animationEnabled: true,
            colorSet: "greenShades",
            title:{
                text: 'رأس المال العامل هو مقدار رأس المال الذى يستخدم بجميع عمليات المشروع ويعد مؤشر قوى لتقييم الاستثمار بالمشروع',
                horizontalAlign: "center",
                fontSize: 20,
            },
            data: [{
                type: "doughnut",
                startAngle: 60,
                //innerRadius: 60,
                indexLabelFontSize: 17,
                indexLabel: "{label}",
                toolTipContent: "<b>{label}</b>",
                dataPoints: {!! json_encode($workingChart) !!}
            }]
        });
        chart.render();

        function toolTipFormatter(e) {
            var str = "";
            var total = 0 ;
            var str3;
            var str2 ;
            for (var i = 0; i < e.entries.length; i++){
                var str1 = "<span style= \"color:"+e.entries[i].dataSeries.color + "\">" + e.entries[i].dataSeries.name + "</span>: <strong>"+  e.entries[i].dataPoint.y + "ر.س</strong> <br/>" ;
                total = e.entries[i].dataPoint.y + total;
                str = str.concat(str1);
            }
            str2 = "<strong>" + e.entries[0].dataPoint.label + "</strong> <br/>";
            // str3 = "<span style = \"color:Tomato\">Total: </span><strong>" + total + "ر.س</strong><br/>";
            return (str2.concat(str));
        }

        function toggleDataSeries(e) {
            if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            }
            else {
                e.dataSeries.visible = true;
            }
            chart.render();
        }
    </script>
    <script>
        @foreach($projectRevenues as $revenue)
            var chart = new CanvasJS.Chart("costAssumption_chart_{{ $revenue->id }}", {
                animationEnabled: true,
                title:{
                    text: "{!! $revenue->name !!}"
                },
                axisY: {
                    title: "",
                    titleFontColor: "#4F81BC",
                    lineColor: "#4F81BC",
                    labelFontColor: "#4F81BC",
                    tickColor: "#4F81BC"
                },
                axisY2: {
                    title: "",
                    titleFontColor: "#C0504E",
                    lineColor: "#C0504E",
                    labelFontColor: "#C0504E",
                    tickColor: "#C0504E"
                },
                toolTip: {
                    shared: true
                },
                legend: {
                    cursor:"pointer",
                    itemclick: toggleDataSeries
                },
                data: [
                    {
                        type: "column",
                        name: "{!! __('first_year') !!}",
                        legendText: "{!! __('first_year') !!}",
                        showInLegend: true,
                        dataPoints:[
                            @foreach($revenue->sources as $source)
                                { label: "{!! $source->name !!}", y: {!! $source->total_revenue * ($planningRevenueOperatingAssumptions->first_year / 100) !!} },
                            @endforeach
                        ]
                    },
                    {
                        type: "column",
                        name: "{!! __('second_year') !!}",
                        legendText: "{!! __('second_year') !!}",
                        showInLegend: true,
                        dataPoints:[
                            @foreach($revenue->sources as $source)
                                { label: "{!! $source->name !!}", y: {!! $source->total_second_revenue * ($planningRevenueOperatingAssumptions->second_year / 100) !!} },
                            @endforeach
                        ]
                    },
                    {
                        type: "column",
                        name: "{!! __('third_year') !!}",
                        legendText: "{!! __('third_year') !!}",
                        showInLegend: true,
                        dataPoints:[
                            @foreach($revenue->sources as $source)
                                { label: "{!! $source->name !!}", y: {!! $source->total_third_revenue * ($planningRevenueOperatingAssumptions->third_year / 100) !!} },
                            @endforeach
                        ]
                    },
                    ]
                });
            chart.render();
        @endforeach

    </script>
    <script>
        $(document).ready(function(){
            $('#first_year_total').text('<?=$calc_total['first_year']?>');
            $('#second_year_total').text('<?=$calc_total['second_year']?>');
            $('#third_year_total').text('<?=$calc_total['third_year']?>');
            $('#profit_before_zakat').text('<?=$calc_total['profit_before_zakat']?>');
            $('#zakat_percent_value').text('<?=$calc_total['zakat_percent_value']?>');
            $('#profit_after_zakat').text('<?=$calc_total['profit_after_zakat']?>');
        });
    </script>
    <script>
        $('#generate_pdf').on('click', function () {
            $('#content').printThis({
                debug: false,               // show the iframe for debugging
                importCSS: true,            // import parent page css
                importStyle: true,         // import style tags
                printContainer: true,       // print outer container/$.selector
                loadCSS: "",                // path to additional css file - use an array [] for multiple
                pageTitle: "{{__('خطتي : التقرير النهائي')}}",              // add title to print page
                removeInline: false,        // remove inline styles from print elements
                removeInlineSelector: "*",  // custom selectors to filter inline styles. removeInline must be true
                printDelay: 333,            // variable print delay
                header: null,               // prefix to html
                footer: null,               // postfix to html
                base: false,                // preserve the BASE tag or accept a string for the URL
                formValues: true,           // preserve input/form values
                canvas: true,              // copy canvas content
                doctypeString: '...',       // enter a different doctype for older markup
                removeScripts: false,       // remove script tags from print content
                copyTagClasses: false,      // copy classes from the html & body tag
                beforePrintEvent: null,     // function for printEvent in iframe
                beforePrint: null,          // function called before iframe is filled
                afterPrint: null            // function called before iframe is removed
            });
        })
    </script>
@endsection