@extends('layouts.primary')
@section('content')
    <style>
        .tooltip-inner{
            background-color: #fff;
        }
        .tooltip[data-active='true'] .tooltiptext {
            visibility: visible;
            opacity: 1;
        }
    </style>
    <div class="card ">
        <div class=" card-body table-responsive">
            <div class="row">
                <h5>{{__('Invested capital planning')}}</h5>
            </div>
            <div id="chartContainer" style="height: 370px; max-width: 920px; margin: 0px auto;"></div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            </div>
        </div>
    </div>

@endsection


@section('script')
    <script src="{{ asset('js/canvasjs/canvasjs.min.js') }}"></script>

    <script>
        window.onload = function () {
            CanvasJS.addColorSet("greenShades",
                [//colorSet Array
                    "#90EE90",
                    "#3CB371",
                    "#257f4b",
                    "#0c4323",
                    "#43f209",
                    "#05941c",
                    "#9fff00",
                    "#bbc7de",
                ]);
            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                colorSet: "greenShades",
                title:{
                    text: '',
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
                    dataPoints: {!! json_encode($data) !!}
                }]
            });
            chart.render();

        }
    </script>

@endsection
