@extends('layouts.primary')
@section('content')
    <div class="card bg-purple-light mb-3 mt-4">
        <div class="card-header bg-purple-light pb-0 p-3">
            <div class="row">
                <div class="col-md-8 ">
                    <h6 >{{__('Fixed capital planning')}} </h6>
                    <p>رأس المال الثابت هو قسم من رأس المال لا يتغير مهما تغير حجم الانتاج ويشمل رأس المال الثابت تكلفة شراء اى عناصر لازمه  لسير المشروع ولا تتغير قيمته بتغير حجم المشروع </p>
                </div>
                <div class="col-md-4 text-right">

                    <a class="btn bg-gradient-dark" href="{{ route('fixedInvested.create') }}"><i class="fas fa-plus"></i>&nbsp;&nbsp;
                        {{__('Add')}}
                    </a>
                    <a href="{{ route('fixedInvested.show') }}" class="btn bg-dark-alt text-white">
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
                    <div class="row">
                        <p>{{__('Elements of fixed capital')}}</p>
                    </div>
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="cloudonex_table">
                            <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">{{__('type')}}</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">{{__('cost')}}</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                            <tbody>
                            @if(count($fixedInvested) > 0)
                                @foreach($fixedInvested as $investor)

                                    <tr>
                                        <td>
                                            <p class=" font-weight-bold mb-0">{{$investor->investing_description}}</p>
                                        </td>
                                        <td class="align-middle text-center">
                                        <span
                                                class="text-secondary font-weight-bold">{{formatCurrency( ($investor->investing_price != 0 ? $investor->investing_price : ''), $currency)}}</span>
                                        </td>
                                        <td class="align-middle text-right">
                                            <div class="ms-auto">
                                                <a class="btn btn-link text-dark px-3 mb-0"
                                                   href="{{ route('fixedInvested.create', ['id' =>$investor->id]) }}"><i
                                                            class="fas fa-pencil-alt text-dark me-2"
                                                            aria-hidden="true"></i>{{__('Edit')}}</a>

                                                <button type="button" class="btn btn-link text-danger text-gradient px-3 mb-0" onclick="deleteItem('{{ route('fixedInvested.destroyCost', ['id' =>$investor->id]) }}')"
                                                   ><i
                                                            class="far fa-trash-alt me-2"></i>{{__('DeleteCost')}}</button>
                                                <button type="button" class="btn btn-link text-danger text-gradient px-3 mb-0" onclick="deleteItem('{{ route('fixedInvested.destroy', ['id' =>$investor->id]) }}')"
                                                   ><i
                                                            class="far fa-trash-alt me-2"></i>{{__('DeleteElement')}}</button>



                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>
                                        <p class=" font-weight-bold mb-0">{{ __('total') }}</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span
                                                class="text-secondary font-weight-bold">{{formatCurrency($fixedInvested->sum('investing_price'),getWorkspaceCurrency($settings))}}</span>
                                    </td>
                                </tr>
                            @endif

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


