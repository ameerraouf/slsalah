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
                {{__('revenue_forecast')}}
            </h5>
        </div>
    </div>
    <div class="card ">
        <div class=" card-body table-responsive">
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
{{--                                <button type="button" onclick="addSource({!! $revenue->id !!})" class="btn-sm btn-info float-end">{{ __('addNewRevenueSource') }}</button>--}}
                            </th>
                        </tr>


                        @foreach($revenue->sources as $source)
                            <tr>
                                <td> @if($revenue->main_unit ==0) {{ $source->name }} @endif</td>
                                <td>{{ formatCurrency($source->total_revenue,$currency) }}</td>
                                <td>{{ formatCurrency($source->total_second_revenue,$currency) }}</td>
                                <td>{{ formatCurrency($source->total_third_revenue,$currency) }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                        <tr>
                            <th>{{ __('total') }}</th>
                            <th class="text-muted text-center">{{ formatCurrency(\App\Models\ProjectRevenuePlanning::calcTotalRevenueFirstYear() ,$currency)}}</th>
                            <th class="text-muted text-center">{{ formatCurrency(\App\Models\ProjectRevenuePlanning::calcTotalRevenueSecondYear(),$currency) }}</th>
                            <th class="text-muted text-center">{{ formatCurrency(\App\Models\ProjectRevenuePlanning::calcTotalRevenueThirdYear() ,$currency)}}</th>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="exampleModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('addNewRevenueSource') }}</h5>
                </div>
                <div class="row m-2 p-3">
                    <form method="post" action="{{ route('addNewRevenueSource') }}">
                        @csrf
                        <input type="hidden" name="ProjectRevenuePlanning" id="ProjectRevenuePlanning" value="">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <input type="text" name="source_name" class="form-control"  placeholder="{{__('source_name')}}">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="unit" class="form-control"   placeholder="{{__('source_unit')}}">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="unit_price" class="form-control"  placeholder="{{__('source_unit_price')}}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function addSource(id) {
$('#ProjectRevenuePlanning').val(id);
$('#exampleModal').modal('show')
}
</script>

@endsection
