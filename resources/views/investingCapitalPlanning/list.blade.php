@extends('layouts.primary')
@section('content')
    <div class="card bg-purple-light mb-3 mt-4">
        <div class="card-header bg-purple-light pb-0 p-3">
            <div class="row">
                <div class="col-md-8 ">
                    <h6 >{{__('Invested capital planning')}} </h6>
                    <p>يمثل راس المال المستثمر {{ $investingSum }}</p>
                    <p>ريال سعودى حيث 60% بقيمة  ريال سعودى يتم تمويلها من  البرامج الحكوميه (صندوق الموارد البشرية) و 40% بقيمة  ريال سعودى من  المستثمرين</p>
                </div>
            </div>
        </div>
    </div>
{{--    <div class="row">--}}
{{--        <div class="col-12">--}}
{{--            <div class="card card-body mb-4">--}}
{{--                <div class="card-body px-0 pt-0 pb-2">--}}
{{--                    <div class="table-responsive p-0">--}}
{{--                        <table class="table align-items-center mb-0" id="cloudonex_table">--}}
{{--                            <thead>--}}
{{--                            <tr>--}}
{{--                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">{{__('Elements of fixed capital')}}</th>--}}
{{--                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">{{__('total cost')}}</th>--}}
{{--                                <th class="text-secondary opacity-7"></th>--}}
{{--                            </tr>--}}
{{--                            <tbody>--}}
{{--                            @foreach($fixedInvested as $investor)--}}

{{--                                <tr>--}}
{{--                                    <td>--}}
{{--                                        <p class=" font-weight-bold mb-0">{{$investor->investing_description}}</p>--}}
{{--                                    </td>--}}
{{--                                    <td class="align-middle text-center">--}}
{{--                                        <span--}}
{{--                                            class="text-secondary font-weight-bold">{{$investor->investing_price}}</span>--}}
{{--                                    </td>--}}
{{--                                    <td class="align-middle text-right">--}}
{{--                                        <div class="ms-auto">--}}
{{--                                            <a class="btn btn-link text-success text-gradient px-3 mb-0"--}}
{{--                                               href="/view-investor?id={{$investor->id}}"><i--}}
{{--                                                    class="far fa-file-alt me-2"></i>{{__('View')}}</a>--}}
{{--                                            <a class="btn btn-link text-dark px-3 mb-0"--}}
{{--                                               href="{{ route('fixedInvested.create', ['id' =>$investor->id]) }}"><i--}}
{{--                                                    class="fas fa-pencil-alt text-dark me-2"--}}
{{--                                                    aria-hidden="true"></i>{{__('Edit')}}</a>--}}

{{--                                            <a class="btn btn-link text-danger text-gradient px-3 mb-0"--}}
{{--                                               href="{{ route('fixedInvested.destroy', ['id' =>$investor->id]) }}"><i--}}
{{--                                                    class="far fa-trash-alt me-2"></i>{{__('Delete')}}</a>--}}



{{--                                        </div>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

@endsection
@section('script')
    <script>
        "use strict";
        $(document).ready(function () {
            $('#cloudonex_table').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json'
                }
            });

        });
    </script>
@endsection


