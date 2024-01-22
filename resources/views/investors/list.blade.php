@extends('layouts.primary')
@section('content')
    <div class="card bg-purple-light mb-3 mt-4">
        <div class="card-header bg-purple-light pb-0 p-3">
            <div class="row justify-content-between">
                <div class="col-md-8 ">
                    @if(count($investors) != 0)
                        <h6 >{{__('Invested capital planning')}} </h6>
                        <p>يمثل راس المال المستثمر {{ $investingSum }} ريال سعودى</p>
                        @foreach($investors as $investor)
                            <p> حيث {{$investor->amount}} بقيمة  ريال سعودى يتم تمويلها من  ({{ $investor->first_name}} {{$investor->last_name }} )</p>
                        @endforeach
                    @endif
                </div>

                <div class="col-md-4 text-right">

                    <a class="btn bg-gradient-dark" href="/add-investor"><i class="fas fa-plus"></i>&nbsp;&nbsp;
                        {{__(' Add New Investor')}}
                    </a>
                    <a href="{{ route('investors.show') }}" class="btn bg-dark-alt text-white">
                        <i class="fa fa-eye me-2"></i>عرض المخطط
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card card-body mb-4">
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="cloudonex_table">
                            <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">{{__('Name')}}</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">{{__('Email')}}</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">{{__('Phone')}}</th>
                                <th class="text-secondary opacity-7">{{__('Actions')}}</th>
                            </tr>
                            <tbody>
                            @foreach($investors as $investor)

                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <div
                                                    class="avatar avatar-md bg-purple-light  border-radius-md p-2 ">
                                                    <h6 class="text-success text-purple">{{$investor->first_name['0']}}{{$investor->last_name['0']}}</h6>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center px-3">
                                                <h6 class="mb-0">{{$investor->first_name}} {{$investor->last_name}}</h6>
                                                <p class="text-sm text-secondary mb-0">{{$investor->email}}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class=" font-weight-bold mb-0">{{$investor->email}}</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span
                                            class="text-secondary font-weight-bold">{{$investor->phone_number}}</span>
                                    </td>
                                    <td class="align-middle text-right">
                                        <div class="ms-auto">
                                            <a class="btn btn-link text-success text-gradient px-3 mb-0"
                                               href="/view-investor?id={{$investor->id}}"><i
                                                    class="far fa-file-alt me-2"></i>{{__('View')}}</a>
                                            <a class="btn btn-link text-dark px-3 mb-0"
                                               href="/add-investor?id={{$investor->id}}"><i
                                                    class="fas fa-pencil-alt text-dark me-2"
                                                    aria-hidden="true"></i>{{__('Edit')}}</a>

                                            <button type="button" class="btn btn-link text-danger text-gradient px-3 mb-0" onclick="deleteItem('/delete/investor/{{$investor->id}}')"
                                              ><i
                                                    class="far fa-trash-alt me-2"></i>{{__('Delete')}}</button>



                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        "use strict";
        $(document).ready(function () {
            $('#cloudonex_table').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json'
                },
                "ordering": false
            });

        });
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
                        window.location.href = url;
                    }
                });
        }
    </script>
@endsection


