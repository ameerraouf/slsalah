@extends('layouts.primary')
@section('content')
    <div class=" row">
        <div class="col">
            <h5 class=" text-secondary fw-bolder" style="color: green !important;">
                يرجى إدخال النسب في الحقول التالية 100% وإذا وجدت نسبة مخاطرة في تحقيق توقعات الإيرادات في السنة الأولى يمكن إدخال نسبة أقل من 100%
                {{--                {{__('planning_revenue_operating_assumptions')}}--}}
            </h5>
        </div>
{{--        <div class="col text-end">--}}
{{--            <a href="#!" onclick="getCreateForm('{{ route('planning_revenue_operating_assumptions.create') }}')" type="button" class="btn btn-info text-white">{{__('add_planning_revenue_operating_assumptions')}}</a>--}}
{{--            <a href="{{ route('revenueForecast') }}"  type="button" class="btn btn-info text-white">{{__('revenue_forecast')}}</a>--}}
{{--        </div>--}}
    </div>
    <div class="card ">
        <div class=" card-body table-responsive">
            <table class="table align-items-center mb-0" id="cloudonex_table">
                <thead>
                <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('first_year')}}</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('second_year')}}</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('third_year')}}</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{__('Action')}}</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <form method="post" action="{{ route('planning_revenue_operating_assumptions.store') }}" onsubmit="submitForm(event,this)" id="PlanningRevenueOperatingAssumptions">@csrf</form>
                        <td style="position: relative">
                            <input class="form-control" onchange="checkNumbers(this)" onfocusin="showHint('first_year_hint')" onfocusout="showHint('first_year_hint')" type="text" min="50" step="0.01" name="first_year" form="PlanningRevenueOperatingAssumptions" value="{{ $planningRevenueOperatingAssumption ? $planningRevenueOperatingAssumption->first_year : 0 }}">
                            <span style="color:#DDD;position: absolute;top: 3px;left: 17px;font-size: xx-large;">%</span>
                            <span style="color:green;display: none;position: fixed;right: 25%;" id="first_year_hint">{{ __('first_year_hint') }}</span>
                        </td>
                        <td style="position: relative">
                            <input class="form-control" onchange="checkNumbers(this)" onfocusin="showHint('second_year_hint')" onfocusout="showHint('second_year_hint')" type="text" min="100" step="0.01" name="second_year" form="PlanningRevenueOperatingAssumptions" value="{{ $planningRevenueOperatingAssumption ? $planningRevenueOperatingAssumption->second_year : 0 }}">
                            <span style="color:#DDD;position: absolute;top: 3px;left: 17px;font-size: xx-large;">%</span>
                            <span style="color:green;display: none;position: fixed;right: 42%;" id="second_year_hint">{{ __('second_year_hint') }}</span>
                        </td>
                        <td style="position: relative">
                            <input class="form-control" onchange="checkNumbers(this)" onfocusin="showHint('third_year_hint')" onfocusout="showHint('third_year_hint')" type="text" min="100" step="0.01" name="third_year" form="PlanningRevenueOperatingAssumptions" value="{{ $planningRevenueOperatingAssumption ? $planningRevenueOperatingAssumption->third_year : 0 }}">
                            <span style="color:#DDD;position: absolute;top: 3px;left: 17px;font-size: xx-large;">%</span>
                            <span style="color:green;display: none;position: fixed;right: 64%;" id="third_year_hint">{{ __('third_year_hint') }}</span>
                        </td>
                        <td>
                            <button type="submit" style="margin: 0px;width: 100%;" form="PlanningRevenueOperatingAssumptions" class="btn btn-primary">{{__('Submit')}}</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

   
@endsection


@section('script')
    <script>

        function showHint(id){
            $('#'+id).toggle();
            $('input').removeClass('is-invalid');
            $('.invalid-feedback').remove();
        }
        function checkNumbers(obj){
            var reg = /^[+-]?\d+(\.\d+)?$/;
            var hash = obj.value;
            if(!reg.test(hash)){
                obj.value = 0;
                Toast.fire({
                    icon: 'error',
                    title: 'يرجى اضافة ارقام فقط'
                });
                obj.focus()
            }
        }
        "use strict";
        $(document).ready(function () {
            $('input').tooltip();
        });
        function submitForm(event,obj){
            // console.log(event,obj);
            $('button[type="submit"]').prop('disabled',true);
            $('input').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            // console.log($(obj).serializeArray());
            event.preventDefault(); // avoid to execute the actual submit of the form.
            $.ajax({
                url:$(obj).attr('action'),
                type:$(obj).attr('method'),
                data:$(obj).serialize(),
                success:function(result){
                    $('button[type="submit"]').prop('disabled',false);
                    Toast.fire({
                        icon: result.type,
                        title: result.msg
                    });
                    // $('#card_body').empty().append(result.revenueForecastsAfterOperatingAssumptions)
                    // .append('<hr>')
                    // .append(result.getIncomeList)
                    // .append('<hr>')
                    // .append(result.getStatementOfCashFlows)
                    // .append('<hr>')
                    // .append(result.capital_investment_model);
                },
                error:function (errors) {
                    $('button[type="submit"]').prop('disabled',false);
                    const entries = Object.entries(errors.responseJSON.errors);
                    // console.log(entries);
                    var errors_message = document.createElement('div');
                    for(let x of entries){
                        if(x[0].includes('.')){
                            var key = x[0].split('.');
                            errors_message = document.createElement('div');
                            errors_message.classList.add('invalid-feedback');
                            errors_message.classList.add('show');
                            errors_message.style.position = 'absolute';
                            document.querySelectorAll('input[name="' + key[0] + '[]"]')[key[1]].classList.add('is-invalid');
                            errors_message.innerHTML = x[1][0];
                            document.querySelectorAll('input[name="' + key[0] + '[]"]')[key[1]].parentElement.appendChild(errors_message);
                        }else {
                            // console.log(document.querySelector('input[name="' + x[0] + '"]'));
                            if (document.querySelector('input[name="' + x[0] + '"]')) {
                                errors_message = document.createElement('div');
                                errors_message.classList.add('invalid-feedback');
                                errors_message.classList.add('show');
                                errors_message.style.position = 'absolute';
                                document.querySelector('input[name="' + x[0] + '"]').classList.add('is-invalid');
                                errors_message.innerHTML = x[1][0];
                                document.querySelector('input[name="' + x[0] + '"]').parentElement.appendChild(errors_message);
                            }
                        }
                    }
                }

            });
            return false;
        }

    </script>

@endsection
