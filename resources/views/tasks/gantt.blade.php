@extends('layouts.primary')



@section('content')

    <div class="row">
        <div class="col">
            <h5 class="text-secondary">{{__('Tasks /Gantt Chart')}}</h5>
        </div>
        <div class="col text-end">
            <a href="/kanban" type="button" class="btn btn-info">

                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trello"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><rect x="7" y="7" width="3" height="9"></rect><rect x="14" y="7" width="3" height="5"></rect></svg>
                {{__(' Kanban')}}
            </a>

            <a href="/admin/tasks/list" type="button" class="btn btn-secondary text-white">

                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-table"><path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18"></path></svg>
                {{__(' Task Table ')}}
            </a>


        </div>
    </div>

    <div>
        <div class="card">
            <div class="card-body">
{{--                <svg id="gantt">--}}

{{--                </svg>--}}
                <style>
                    .vr{
                        background-image: linear-gradient(#67748e9e, #67748e9e);
                        background-size: 2px 100%;
                        background-repeat: no-repeat;
                        background-position: center center;
                    }
                </style>
                <div id="gnt" style="text-align: center;">
                    <div class="row" style="border-bottom: 1px solid #DDD;padding: 10px;">
                        <div class="col-md-2"></div>
                        @foreach($date_list as $key => $date)
                            <div class="col-md-2" style="text-align: right;margin-right: -{{ 25 - ($key *6) }}px">{{ $date }}</div>
                        @endforeach
                    </div>

                    @foreach($tasks as $key => $task)
                        <div class="row" id="row_{{ $task->id }}" style="border-bottom: 1px solid #DDD;">
                            <div class="col-md-2">{{ $task->subject }}</div>
                            @foreach($date_list as $key1 => $date)
                                <div class="col-md-2" id="col_{{ $task->id }}_{{ str_replace('-','',$date) }}" style="position:relative;border-right:1px solid #000;padding: 30px;"></div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

@endsection

@section('script')

    <script>
        var tasks = @json($tasksLocations);
        var latest_date = @json($date_list[count($date_list)-1]);
        // console.log(latest_date , tasks[1].due_date);
        {{--var date_list = @json($date_list);--}}
        var myTimeout = 0;
        var i = 0;
        (function(){
            for(let task of tasks){
                printBars(task,task.id);
            }
            myTimeout= setTimeout(()=>{
                if(i){
                    clearTimeout(myTimeout);
                }
                i++;
                $('.bar_div').tooltip({});
            }, 100);
        })();
        function printBars(task,task_id){
            var first_part_printing_td_id = '';
            var first_part_dateDivDimensions = '';
            var first_part_start_position = '';
            var first_part_width = '';
            var last_part_printing_td_id = '';
            var last_part_dateDivDimensions = '';
            var last_part_start_position = '';
            var last_part_width = '';
            var dateDivDimensions = '';
            color = '#8392AB';
            if(task.task_status == 'done'){
                color = '#71DD38';
            }else if(task.task_status == 'in_progress'){
                color = '#0d6efd';
            }else if(task.task_status == 'in_review'){
                color = '#E7C345';
            }else if(task.task_status == 'Not Started'){
                color = '#17c1e8';
            }

            if(task.between.length>0){
                first_part_printing_td_id = 'col_'+task_id+'_'+formatDatePure(task.first_date_boundaries[0]);
                first_part_dateDivDimensions = document.getElementById(first_part_printing_td_id).getBoundingClientRect();
                first_part_start_position = first_part_dateDivDimensions.width * task.first_date_position_pixels_percent;
                first_part_width = first_part_dateDivDimensions.width - first_part_start_position;

                last_part_printing_td_id = 'col_'+task_id+'_'+formatDatePure(task.due_date_boundaries[0]);
                last_part_dateDivDimensions = document.getElementById(last_part_printing_td_id).getBoundingClientRect();
                last_part_start_position = 0;
                last_part_width = (task.due_date_position_pixels_percent == 0 && latest_date != task.due_date) ? last_part_dateDivDimensions.width : ((task.due_date_position_pixels_percent == 0 && latest_date == task.due_date && task.due_date_boundaries[0] == task.due_date_boundaries[1]) ? last_part_dateDivDimensions.width : last_part_dateDivDimensions.width * task.due_date_position_pixels_percent);
                // if(task_id == 16) {
                //     console.log(last_part_printing_td_id, last_part_start_position,last_part_width);
                // }
                $('#'+first_part_printing_td_id).append('<div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="'+task.start_date+' - '+task.due_date +'" class="bar_div" style="position:absolute;top:'+((first_part_dateDivDimensions.height - 10)/2)+'px;right:'+first_part_start_position+'px;height: 10px;width: '+first_part_width+'px;background-color: '+ color+';"></div>');
                if(latest_date == task.due_date && task.due_date_boundaries[0] == task.due_date_boundaries[1]){
                    $('#' + last_part_printing_td_id).append('<div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="' + task.start_date + ' - ' + task.due_date + '" class="bar_div" style="position:absolute;top:' + ((first_part_dateDivDimensions.height - 10) / 2) + 'px;right:-' + last_part_width + 'px;height: 10px;width: ' + last_part_width + 'px;background-color: '+ color+';"></div>');
                }else {
                    $('#' + last_part_printing_td_id).append('<div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="' + task.start_date + ' - ' + task.due_date + '" class="bar_div" style="position:absolute;top:' + ((first_part_dateDivDimensions.height - 10) / 2) + 'px;right:' + last_part_start_position + 'px;height: 10px;width: ' + last_part_width + 'px;background-color: '+ color+';"></div>');
                }
                for(let x in task.between){

                    if(x == 0 && latest_date == task.due_date) {
                        dateDivDimensions = document.getElementById('col_' + task_id + '_' + formatDatePure(task.between[x])).getBoundingClientRect();
                        $('#col_' + task_id + '_' + formatDatePure(task.between[x])).append('<div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="'+task.start_date+' - '+task.due_date +'" class="bar_div" style="position:absolute;top:' + ((dateDivDimensions.height-10) / 2) + 'px;right:0px;height: 10px;width: ' + dateDivDimensions.width + 'px;background-color: '+ color+';"></div>');
                    }else if(x != (task.between.length - 1)) {
                        dateDivDimensions = document.getElementById('col_' + task_id + '_' + formatDatePure(task.between[x])).getBoundingClientRect();
                        $('#col_' + task_id + '_' + formatDatePure(task.between[x])).append('<div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="'+task.start_date+' - '+task.due_date +'" class="bar_div" style="position:absolute;top:' + ((dateDivDimensions.height-10) / 2) + 'px;right:0px;height: 10px;width: ' + dateDivDimensions.width + 'px;background-color: '+ color+';"></div>');
                    }
                }
            }else{
                if(task.in_same_div){
                    var printing_td_id = 'col_'+task_id+'_'+formatDatePure(task.first_date_boundaries[0]);
                    dateDivDimensions = document.getElementById(printing_td_id).getBoundingClientRect();
                    var start_position = dateDivDimensions.width * task.first_date_position_pixels_percent;
                    var end_position = task.due_date_position_pixels_percent == 0 ? 0 : dateDivDimensions.width - (dateDivDimensions.width * task.due_date_position_pixels_percent);
                    var width = dateDivDimensions.width - start_position - end_position;
                    // if(task_id == 6) {
                    //     console.log(dateDivDimensions.width - start_position - end_position, dateDivDimensions.width, start_position, end_position);
                    // }
                    $('#'+printing_td_id).append('<div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="'+task.start_date+' - '+task.due_date +'" class="bar_div" style="position:absolute;top:'+((dateDivDimensions.height - 10)/2)+'px;right:'+start_position+'px;height: 10px;width: '+width+'px;background-color: '+ color+';"></div>');

                }else{
                    first_part_printing_td_id = 'col_'+task_id+'_'+formatDatePure(task.first_date_boundaries[0]);
                    first_part_dateDivDimensions = document.getElementById(first_part_printing_td_id).getBoundingClientRect();
                    first_part_start_position = first_part_dateDivDimensions.width * task.first_date_position_pixels_percent;
                    first_part_width = first_part_dateDivDimensions.width - first_part_start_position;

                    last_part_printing_td_id = 'col_'+task_id+'_'+formatDatePure(task.due_date_boundaries[0]);
                    last_part_dateDivDimensions = document.getElementById(last_part_printing_td_id).getBoundingClientRect();
                    last_part_start_position = 0;
                    last_part_width = task.due_date_position_pixels_percent == 0 ? last_part_dateDivDimensions.width : last_part_dateDivDimensions.width * task.due_date_position_pixels_percent;

                    $('#'+first_part_printing_td_id).append('<div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="'+task.start_date+' - '+task.due_date +'" class="bar_div" style="position:absolute;top:'+((first_part_dateDivDimensions.height - 10)/2)+'px;right:'+first_part_start_position+'px;height: 10px;width: '+first_part_width+'px;background-color: '+ color+';"></div>');
                    $('#'+last_part_printing_td_id).append('<div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="'+task.start_date+' - '+task.due_date +'" class="bar_div" style="position:absolute;top:'+((first_part_dateDivDimensions.height - 10)/2)+'px;right:'+last_part_start_position+'px;height: 10px;width: '+last_part_width+'px;background-color: '+ color+';"></div>');
                }
            }
        }
        function formatDatePure(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            return [year, month, day].join('');
        }
    </script>
        @endsection
