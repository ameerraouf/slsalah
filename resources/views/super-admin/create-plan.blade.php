@extends('layouts.super-admin-portal')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-9 col-12 mx-auto">
                <div class="card card-body">
                    <h6 class="mb-0">
                        @if ($plan)
                            {{ __('Edit Plan') }}
                        @else
                            {{ __('New Plan') }}
                        @endif
                    </h6>
                    <p class="text-sm mb-0">
                        @if ($plan)
                            {{ __('Edit Current plan') }}
                        @else
                            {{ __('Create new plan') }}
                        @endif
                    </p>
                    <form action="/save-subscription-plan" method="post">
                        @if ($errors->any())
                            <div class="alert bg-pink-light text-danger">
                                <ul class="list-unstyled">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <hr class="horizontal dark my-3">



                        <div class="mb-3">
                            <label for="projectName" class="form-label">{{ __('Plan Name') }}</label><label
                                class="text-danger">*</label>
                            <input type="text" @if ($plan) readonly @endif class="form-control"
                                name="name" value="{{ $plan->name ?? (old('name') ?? '') }}" id="projectName">
                        </div>

                        <div class="mb-3">
                            <label for="projectName" class="form-label mt-3">{{ __('Maximum Allowed Users') }}</label><label
                                class="text-danger">*</label>
                            <input type="number" @if ($plan) readonly @endif class="form-control"
                                name="maximum_allowed_users"
                                value="{{ $plan->maximum_allowed_users ?? (old('maximum_allowed_users') ?? '') }}"
                                id="projectName">
                                
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_file_upload_size"
                                        class="form-label">{{ __('Maximum File Upload Size') }}
                                        ({{ __('kb') }})</label><label class="text-danger">*</label>
                                    <input type="text" @if ($plan) readonly @endif
                                        class="form-control" name="max_file_upload_size"
                                        value="{{ $plan->max_file_upload_size ?? (old('max_file_upload_size') ?? '') }}"
                                        id="max_file_upload_size">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="file_space_limit" class="form-label">نوع الملفات المسموح بها </label><label
                                        class="text-danger">*</label>
                                    <input type="text" class="form-control"
                                        @if ($plan) readonly @endif name="file_space_limit"
                                        value="{{ $plan->file_space_limit ?? (old('file_space_limit') ?? '') }}"
                                        id="file_space_limit">

                                </div>
                            </div>
                        </div>




                        <div class="row mt-4">
                            <label>
                                {{ __('Pricing') }}
                            </label>
                            <div class="col-6">
                                <label class="form-label">{{ __('Monthly') }}</label><label class="text-danger">*</label>
                                <input class="form-control datetimepicker" type="number" min="0"
                                    name="price_monthly" value="{{ $plan->price_monthly ?? (old('price_monthly') ?? '') }}"
                                    data-input>
                            </div>
                            <div class="col-6">
                                <label class="form-label">{{ __('offer_price_monthly') }}</label>
                                <input class="form-control datetimepicker" name="offer_price_monthly" type="number"
                                    min="0"
                                    value="{{ $plan->offer_price_monthly ?? (old('offer_price_monthly') ?? '') }}"
                                    data-input>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-6">
                                <label class="form-label">{{ __('percentage_discount_annual') }}</label>
                                <input class="form-control" type="number" min="0" name="percentage_discount_annual"
                                    value="{{ $plan->percentage_discount_annual ?? (old('percentage_discount_annual') ?? '') }}"
                                    data-input>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-6">
                                <label class="form-label">{{ __('Yearly') }}</label><label class="text-danger">*</label>
                                <input class="form-control datetimepicker" name="price_yearly" type="number" min="0"
                                    disabled value="{{ $plan->price_yearly ?? (old('price_yearly') ?? '') }}" data-input>
                            </div>

                            <div class="col-6">
                                <label class="form-label">{{ __('offer_price_yearly') }}</label><label
                                    class="text-danger">*</label>
                                <input class="form-control datetimepicker" type="number" disabled min="0"
                                    name="offer_price_yearly"
                                    value="{{ $plan->offer_price_yearly ?? (old('offer_price_yearly') ?? '') }}"
                                    data-input>
                            </div>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="paypal_plan_id" class="form-label">{{ __('وصف نصي ') }}</label><label
                                class="text-danger">*</label>
                            <input type="text" class="form-control" name="paypal_plan_id"
                                value="{{ $plan->paypal_plan_id ?? (old('name') ?? '') }}" id="paypal_plan_id">
                        </div>

                        <label
                            class="text-uppercase text-body text-xs font-weight-bolder mt-4">{{ __('Modules') }}</label><label
                            class="text-danger">*</label>
                        <ul class="list-group">
                            <li class="list-group-item border-0 px-0">
                                <div class="form-check form-switch ps-0">
                                    <input class="form-check-input ms-auto" @if ($plan)
                                    readonly
                                    @endif  type="checkbox" id="module_all"
                                        name="module_all" value="1" @if (count($available_modules) == count(isset($plan_modules) ? $plan_modules : [])) checked @endif>
                                    <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0"
                                        for="module_all">الكل</label>
                                </div>
                            </li>
                            @foreach ($available_modules as $key => $module)
                                <li class="list-group-item border-0 px-0">
                                    <div class="form-check form-switch ps-0">
                                        <p>{{ $module['title'] }}</p>
                                    </div>
                                </li>

                                @foreach ($module['modules'] as $key2 => $item)
                                    <li class="list-group-item border-0 px-0">
                                        <div class="form-check form-switch ps-0">
                                            <input class="form-check-input ms-auto model-input"
                                                @if ($plan) readonly @endif type="checkbox"
                                                id="module_{{ $key }}" name="modules[{{ $key2 }}]"
                                                @if (!empty($plan_modules) && in_array($key2, $plan_modules)) checked @endif>
                                            <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0"
                                                for="module_{{ $key }}">{!! $item !!}</label>
                                        </div>
                                    </li>
                                @endforeach
                            @endforeach
                        </ul>

                        <label class="mt-4">{{ __('Description') }}</label><label class="text-danger">*</label>

                        <div class="form-group">
                            <textarea class="form-control" rows="10" id="description" name="description">
                                @if (!empty($plan))
{{ $plan->description }}
@else
{{ old('description') ?? '' }}
@endif
</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Features') }}</label>
                            <lable class="text-danger">*</lable>
                            <div id="div_features">

                                @if (!empty($features))
                                    @foreach ($features as $feature)
                                        <div class="row feature_row">
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="features[]"
                                                    value="{!! $feature !!}">
                                            </div>
                                            <div class="col-md-3 text-end">
                                                <button class="btn btn-sm btn-danger btn_remove_feature"><i
                                                        class="fas fa-minus"></i> </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                {{--                                <div class="row feature_row"> --}}
                                {{--                                    <div class="col-md-9"> --}}
                                {{--                                        <input type="text" class="form-control" name="features[]"> --}}
                                {{--                                    </div> --}}
                                {{--                                    <div class="col-md-3 text-end"> --}}
                                {{--                                        <button class="btn btn-sm btn-danger btn_remove_feature"><i class="fas fa-minus"></i> </button> --}}
                                {{--                                    </div> --}}
                                {{--                                </div> --}}
                            </div>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-sm btn-dark" id="btn_add_feature"><i class="fas fa-plus"></i>
                            </button>
                        </div>


                        <div class="form-check form-switch ps-0">
                            <input class="form-check-input ms-auto" type="checkbox" id="active" name="active"
                                value="1" @if ($plan && !$plan->active) checked @endif>
                            <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0"
                                for="active">تعطيل</label>
                        </div>
                        @csrf
                        @if ($plan)
                            <input type="hidden" name="id" value="{{ $plan->id }}">
                        @endif
                        <div class="d-flex  mt-4">

                            <button type="submit" name="button" class="btn btn-info m-0 ">{{ __('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function() {
            "use strict";
            flatpickr("#start_date", {

                dateFormat: "Y-m-d",
            });

            flatpickr("#end_date", {

                dateFormat: "Y-m-d",
            });

            tinymce.init({
                selector: '#description',
                language: 'ar',
                language_url: '{{ PUBLIC_DIR }}/js/tinymce.ar.js',

                plugins: 'table,code',


            });

            let $btn_add_feature = $('#btn_add_feature');
            let $div_features = $('#div_features');

            $btn_add_feature.on('click', function(event) {
                event.preventDefault();
                $div_features.append(
                    '<div class="row feature_row"><div class="col-md-9"><input type="text" class="form-control" name="features[]"></div><div class="col-md-3 text-end"><button class="btn btn-sm btn-danger btn_remove_feature"><i class="fas fa-minus"></i> </button></div></div>'
                );

            });

            let $clx_body = $('#clx_body');

            $clx_body.on('click', '.btn_remove_feature', function(event) {
                event.preventDefault();
                $(this).closest('.feature_row').remove();
            });

            $('#module_all').change(function() {
                if ($(this).is(':checked')) {
                    $('.model-input').prop('checked', true)
                } else {
                    $('.model-input').prop('checked', false)
                }
            });

            $('input[name=price_monthly]').on('change', function() {
                let price = parseInt($(this).val());
                $('input[name=price_yearly]').val(12 * price);
            });
            $('input[name=percentage_discount_annual]').on('change', function() {
                let price = parseInt($('input[name=price_yearly]').val());
                let value = price - (price * ($(this).val() / 100));
                $('input[name=offer_price_yearly]').val(value);



                $.ajax({
                    url: '{{ route('offer_price_yearly') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        value: value
                    },

                    success: function(response) {
                        console.log('Session value set successfully');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error setting session value:', error);
                    }
                });
            });
        });
    </script>
@endsection
