
<div class="row mb-3">
    <div class="col-md-5">
        <label for="exampleInput" class="form-label">{{ __('project_revenue_plan_name') }}</label>
        <input type="text" name="name" class="form-control" id="exampleInput" value="{{ $projectRevenuePlanning->name }}">
        <div class="form-text"></div>
    </div>

    <div class="col-md-2">
        <button type="button" class="btn btn-info" style="margin: 0px;margin-top: 32px;" onclick="addNewRevenueSourceRow(this)">{{ __('addNewRevenueSource') }}</button>
    </div>
    <div class="row" id="main_revenue_data" @if(!empty($projectRevenuePlanning) && $projectRevenuePlanning->sources->count() && empty($projectRevenuePlanning->main_unit)) style="display: none;" @endif>
        <div class="col-md-6">
            <label for="exampleInputUnit" class="form-label">{{__('source_unit')}}</label>
            <input type="text" name="main_unit" class="form-control" id="exampleInputUnit" value="{{ $projectRevenuePlanning->main_unit }}">
            <div class="form-text"></div>
        </div>
        <div class="col-md-6">
            <label for="exampleInputUnitPrice" class="form-label">{{__('source_unit_price')}}</label>
            <input type="text" name="main_unit_price" class="form-control" id="exampleInputUnitPrice" value="{{ $projectRevenuePlanning->main_unit_price }}">
            <div class="form-text"></div>
        </div>
    </div>
    @if(!empty($projectRevenuePlanning) && $projectRevenuePlanning->sources->count() && empty($projectRevenuePlanning->main_unit))
        @foreach($projectRevenuePlanning->sources as $source)
            <div class="row m-2 sources">
                <div class="col-md-4">
                    <input type="text" name="source_name[]" class="form-control" value="{{ $source->name }}" placeholder="{{__('source_name')}}">
                </div>
                <div class="col-md-4">
                    <input type="text" name="unit[]" class="form-control" value="{{ $source->unit }}" placeholder="{{__('source_unit')}}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="unit_price[]" class="form-control" value="{{ $source->unit_price }}" placeholder="{{__('source_unit_price')}}">
                </div>
                <div class="col-md-1">
                    <i class="fa fa-trash text-danger" onclick="deleteSource(this,'{{ $source->id }}')" style="margin-top: 14px;"></i>
                </div>
            </div>
        @endforeach
    @endif
</div>
