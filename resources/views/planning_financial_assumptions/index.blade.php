@extends('layouts.primary')

@section('content')
    <div class=" row">
        <div class="col">
            <h5 class=" text-secondary fw-bolder">
{{--                {{__('planning_financial_assumptions')}}--}}
            </h5>
        </div>
        <div class="col text-end">
{{--            <a href="#!" onclick="getCreateForm('{{ route('planning_financial_assumptions.create') }}')" type="button" class="btn btn-info text-white">{{__('add_planning_financial_assumptions')}}</a>--}}
{{--            <a href="{{ route('revenueForecast') }}"  type="button" class="btn btn-info text-white">{{__('revenue_forecast')}}</a>--}}
        </div>
    </div>
    <div class="card ">
        <div class=" card-body table-responsive">
            <table class="table align-items-center mb-0" id="cloudonex_table">
                <thead>
                <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('net_profit')}}</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('cash_percentage_of_net_profit')}}</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{__('Action')}}</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <form method="post" action="{{ route('planning_financial_assumptions.store') }}" onsubmit="submitForm(event,this)" id="PlanningFinancialAssumptions">@csrf</form>
                        <td style="position: relative">
                            <input class="form-control" onchange="checkNumbers(this)" type="text" min="1" step="0.01" max="100" id="net_profit" name="net_profit" onfocusin="showHint('planningCostAssumptionNetProfitHint')" onfocusout="showHint('planningCostAssumptionNetProfitHint')" form="PlanningFinancialAssumptions" value="{{ $planningFinancialAssumption ? $planningFinancialAssumption->net_profit : 0 }}">
                            <span style="color:#DDD;position: absolute;top: 3px;left: 17px;font-size: xx-large;">%</span>
                            <span style="color:green;display: none;position: fixed;right: 25%;" id="planningCostAssumptionNetProfitHint">{{ $planningCostAssumptionNetProfitHint }}</span>
                        </td>
                        <td style="position: relative">
                            <input class="form-control" onchange="checkNumbers(this)" type="text" min="5" step="0.01" max="20" id="cash_percentage_of_net_profit" name="cash_percentage_of_net_profit" form="PlanningFinancialAssumptions" onfocusin="showHint('cash_percentage_of_net_profit_hint')" onfocusout="showHint('cash_percentage_of_net_profit_hint')" value="{{ $planningFinancialAssumption ? $planningFinancialAssumption->cash_percentage_of_net_profit : 0 }}">
                            <span style="color:#DDD;position: absolute;top: 3px;left: 17px;font-size: xx-large;">%</span>
                            <span style="color:green;display: none;position: fixed;right: 51%;" id="cash_percentage_of_net_profit_hint">{{ __('cash_percentage_of_net_profit_hint') }}</span>
                        </td>
                        <td>
                            <button type="submit" style="margin: 0px;width: 100%;" form="PlanningFinancialAssumptions" class="btn btn-primary">{{__('Submit')}}</button>
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
         event.preventDefault(); // avoid to execute the actual submit of the form.
          if($("#net_profit").val() === '0' || $("#net_profit").val() ==''){
               Toast.fire({
                    icon: 'error',
                    title: 'يرجى اضافة ارقام فقط ولاتدع الحقل فارغ'
                });
                return 0;
          }
            if($("#cash_percentage_of_net_profit").val() === '0' || $("#cash_percentage_of_net_profit").val() ==''){
               Toast.fire({
                    icon: 'error',
                    title: 'يرجى اضافة ارقام فقط ولاتدع الحقل فارغ'
                });
                return 0;
          }

            // console.log(event,obj);
            $('button[type="submit"]').prop('disabled',true);
            $('input').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            // console.log($(obj).serializeArray());

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
