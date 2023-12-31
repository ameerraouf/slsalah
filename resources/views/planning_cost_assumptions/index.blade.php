@extends('layouts.primary')
@section('content')
    <div class=" row">
        <div class="col">
            <h5 class=" text-secondary fw-bolder" style="color: green">
                ملحوظة: إجمالي النسب المراد إدخالها بالتكاليف التشغيلية والمصروفات العمومية و المصاريف التسويقية وصافي الربح يجب أن تساوي 100% من قيمة الإيرادات
{{--                {{__('planning_cost_assumptions')}}--}}
            </h5>
        </div>
    </div>
    <div class="card ">
        <div class=" card-body table-responsive">
            <table class="table align-items-center mb-0" id="cloudonex_table">
                <thead>
                <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('operational_costs')}}</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('general_expenses')}}</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('marketing_expenses')}}</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{__('Action')}}</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <form method="post" action="{{ route('planning_cost_assumptions.store') }}" onsubmit="submitForm(event,this)" id="PlanningCostAssumptions">@csrf</form>
                        <td style="position: relative">
                            <input class="form-control" onchange="checkNumbers(this)" type="text" min="1" step="0.01" max="100" name="operational_costs" onfocusin="showHint('operational_costs_hint')" onfocusout="showHint('operational_costs_hint')" form="PlanningCostAssumptions" value="{{ $planningCostAssumption ? $planningCostAssumption->operational_costs : 0 }}">
                            <span style="color:#DDD;position: absolute;top: 3px;left: 17px;font-size: xx-large;">%</span>
                            <span style="color:green;display: none;position: fixed;right: 25%;" id="operational_costs_hint">{{ __('operational_costs_hint') }}</span>
                        </td>
                        <td style="position: relative">
                            <input class="form-control" onchange="checkNumbers(this)" type="text" min="5" step="0.01" max="20" name="general_expenses" onfocusin="showHint('general_expenses_hint')" onfocusout="showHint('general_expenses_hint')" form="PlanningCostAssumptions" value="{{ $planningCostAssumption ? $planningCostAssumption->general_expenses : 0 }}">
                            <span style="color:#DDD;position: absolute;top: 3px;left: 17px;font-size: xx-large;">%</span>
                            <span style="color:green;display: none;position: fixed;right: 42%;" id="general_expenses_hint">{{ __('general_expenses_hint') }}</span>
                        </td>
                        <td style="position: relative">
                            <input class="form-control" onchange="checkNumbers(this)" type="text" min="5" step="0.01" max="20" name="marketing_expenses" onfocusin="showHint('marketing_expenses_hint')" onfocusout="showHint('marketing_expenses_hint')" form="PlanningCostAssumptions" value="{{ $planningCostAssumption ? $planningCostAssumption->marketing_expenses : 0 }}">
                            <span style="color:#DDD;position: absolute;top: 3px;left: 17px;font-size: xx-large;">%</span>
                            <span style="color:green;display: none;position: fixed;right: 64%;" id="marketing_expenses_hint">{{ __('marketing_expenses_hint') }}</span>
                        </td>
                        <td>
                            <button type="submit" style="margin: 0px;width: 100%;" form="PlanningCostAssumptions" class="btn btn-primary">{{__('Submit')}}</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection


@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    // $('#exampleModal').modal('hide');
                    Toast.fire({
                        icon: result.type,
                        title: result.msg
                    });
                    // $('#cloudonex_table').DataTable().ajax.reload();
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
