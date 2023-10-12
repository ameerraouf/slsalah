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
    <div class=" row">
        <div class="col">
            <h5 class=" text-secondary fw-bolder">
                {{__('project_revenue_planning')}}
            </h5>
        </div>
        <div class="col text-end">
            <a href="#!" onclick="getCreateForm('{{ route('project-revenue-planning.create') }}')" type="button" class="btn btn-info text-white">{{__('add_project_revenue_planning')}}</a>
            <a href="#"  type="button" data-bs-toggle="modal" data-bs-target="#yearlyIncreasingPercentage" class="btn btn-info text-white">{{__('yearly_increasing_percentage')}}</a>
            <a href="{{ route('revenueForecast') }}"  type="button" class="btn btn-info text-white">{{__('revenue_forecast')}}</a>
        </div>
    </div>
    <div class="card ">
        <div class=" card-body table-responsive">
            <table class="table align-items-center mb-0" id="cloudonex_table">
                <thead>
                <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('project_revenue_planning_name')}}</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('yearly_increasing_percentage')}}</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('project_revenue_planning_sources')}}</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{__('Action')}}</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="exampleModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <div class="modal fade" id="yearlyIncreasingPercentage">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('yearly_increasing_percentage') }}</h5>

                </div>
                <form method="post" action="{{ route('project-revenue-planning.saveYearlyIncreasingPercentage') }}">
                    @csrf
                    <div class="row p-3">
                        <div class="col-md-5">
                            <label for="exampleInput1" class="form-label">{{ __('yearly_increasing_percentage') }}</label>
                            <input type="number" min="0" max="100" step="0.01" name="yearly_increasing_percentage" class="form-control" id="exampleInput1" value="{{ $yearlyIncrease }}">
                            <div class="form-text"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@section('script')
    <script>

        "use strict";
        $(document).ready(function () {
            $('#cloudonex_table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('project-revenue-planning.getData') }}",
                "pagingType": "full_numbers",
                "drawCallback": function( settings ) {
                    $('.cut-text').tooltip({
                        html: true,
                        sanitize:false
                    });
                },
                "columnDefs": [
                    { "sortable": false, "targets": [3,4] },
                    { className: "white-space cut-text", targets: [3]  }
                ],
                "aoColumns": [
                    { "mData": "id" },
                    { "mData": "name" },
                    { "mData": "yearly_increasing_percentage" },
                    { "mData": "sourcesCount" },
                    { "mData": "tools" }
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json'
                },
                'createdRow': function( row, data, dataIndex ) {
                    $(row).find('.cut-text').attr('data-bs-toggle',"tooltip");
                    $(row).find('.cut-text').attr('data-bs-placement',"top");
                    $($(row).find('.cut-text')[0]).attr('title',data.sources);
                }
            });
        });
        function getCreateForm(link){
            // console.log(link);
            $('#exampleModal').modal('show');
            $('#exampleModal .modal-content').empty().append('<div class="spinner-grow text-success" style="align-self: center;" role="status"><span class="visually-hidden"></span></div>');
            $.get(link,function (data){
                $('#exampleModal .modal-content').empty().append(data);
            });
        }
        function getUpdateForm(link){
            // console.log(link);
            $('#exampleModal').modal('show');
            $('#exampleModal .modal-content').empty().append('<div class="spinner-grow text-success" style="align-self: center;" role="status"><span class="visually-hidden"></span></div>');
            $.get(link,function (data){
                $('#exampleModal .modal-content').empty().append(data);
            });
        }
        function deleteItem(url) {

            Swal.fire({
                title: 'هل أنت متأكد ؟',
                text: "لن تستطيع التراجع",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم ، احذف !',
                cancelButtonText: 'لا !'
            }).then(
                function(result){
                    if (result.value){
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            },
                            url: url,
                            type: 'post',
                            data: {_method:'delete'},
                            success: function (result) {
                                Toast.fire({
                                    icon: result.type,
                                    title: result.msg
                                });
                                $('#cloudonex_table').DataTable().ajax.reload();
                                // return result;
                            }
                        });
                    }
                });
        }

        function addNewRevenueSourceRow(obj){
            $('#main_revenue_data').hide();
            $(obj).parent().parent().append('<div class="row m-2 sources"> '+
                '<div class="col-md-4"> '+
                    '<input type="text" name="source_name[]" class="form-control" placeholder="{{__('source_name')}}"> '+
                '</div> '+
                '<div class="col-md-4"> '+
                    '<input type="text" name="unit[]" class="form-control" placeholder="{{__('source_unit')}}"> '+
                '</div> '+
                '<div class="col-md-3"> '+
                    '<input type="text" name="unit_price[]" class="form-control" placeholder="{{__('source_unit_price')}}"> '+
                '</div> '+
                '<div class="col-md-1"> '+
                    '<i class="fa fa-trash text-danger" onclick="deleteSource(this)" style="margin-top: 14px;"></i> '+
                '</div> '+
            '</div>');
        }
        function deleteSource(obj,id=0){
            $(obj).parent().parent().remove();
            if(!$('.sources').length){
                $('#main_revenue_data').show();
            }
            if(id!=0){
                $.get('/deleteRevenueSource/'+id,function (data){
                    Toast.fire({
                        icon: data.type,
                        title: data.msg
                    });
                    $('#cloudonex_table').DataTable().ajax.reload();
                });
            }
        }
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
                    $('#exampleModal').modal('hide');
                    Toast.fire({
                        icon: result.type,
                        title: result.msg
                    });
                    $('#cloudonex_table').DataTable().ajax.reload();
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
                            document.querySelectorAll('input[name="' + key[0] + '[]"]')[key[1]].classList.add('is-invalid');
                            errors_message.innerHTML = x[1][0];
                            document.querySelectorAll('input[name="' + key[0] + '[]"]')[key[1]].parentElement.appendChild(errors_message);
                        }else {
                            // console.log(document.querySelector('input[name="' + x[0] + '"]'));
                            if (document.querySelector('input[name="' + x[0] + '"]')) {
                                errors_message = document.createElement('div');
                                errors_message.classList.add('invalid-feedback');
                                errors_message.classList.add('show');
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
