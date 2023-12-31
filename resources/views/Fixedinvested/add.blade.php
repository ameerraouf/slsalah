@extends('layouts.primary')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="multisteps-form mb-5">
                <!--form panels-->
                <div class="row">
                    <div class="col-12 col-lg-8 m-auto">
                        <form action="{{ route('fixedInvested.store') }}" method="post" class="multisteps-form__form mb-8">
                            <!--single form panel-->
                            @if ($errors->any())
                                <div class="alert bg-pink-light text-danger">
                                    <ul class="list-unstyled">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="card card-body p-3  js-active" data-animation="FadeIn">
                                <h5 class="font-weight-bolder mb-0">
                                    @if(!isset($investor)) {{ __('Add') }} @else {{ __('Edit') }} @endif {{ __('Element of fixed capital') }}

                                </h5>

                                <div class="multisteps-form__content">
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label>{{ __('type') }}</label><small class="text-danger">*</small>
                                            <input name="investing_description" class="multisteps-form__input form-control" type="text"
                                                value="{{ $investor->investing_description ?? (old('investing_description') ?? '') }}" />
                                        </div>

                                    </div>
                            </div>
                                <div class="multisteps-form__content">
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label>{{ __('cost') }}</label><small class="text-danger">*</small>
                                            <input name="investing_price" class="multisteps-form__input form-control" type="number"
                                                value="{{ (isset($investor) && $investor->investing_price != 0) ? $investor->investing_price : old('investing_price')}}" />
                                        </div>

                                    </div>
                            </div>
                            <!--single form panel-->
                            @csrf
                            @if (!empty($investor))
                                <input type="hidden" name="id" value="{{ $investor->id }}">
                            @endif
                            <div class="button-row text-left mt-4 ">
                                <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next" type="submit"
                                    title="Next">{{ __('Submit') }}</button>
                            </div>
                        </form>
                    </div>
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
                selector: '#editor',
                language: 'ar',
                language_url: '{{ PUBLIC_DIR }}/js/tinymce.ar.js',
                toolbar: 'styleselect | forecolor | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | link image | code | undo redo|numlist bullist',
                lists_indent_on_tab: false,
                branding: false,
                menubar: false,


            });

        });
    </script>
@endsection
