@extends('layouts.super-admin-portal')
@section('content') 
<div class="container-fluid" id="main_content">

    <div class="row">
        <div class="col-12">
            <div class="multisteps-form mb-5">
                <div class="row">
                    <div class="col-12 col-lg-8 m-auto"> 
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="list-unstyled">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                        <form action="{{ route('video.update',$video->id) }}" method="post" class="multisteps-form__form mb-8">
                            <!--single form panel-->
                            @csrf 
                            @method('put')
                            <div class="card multisteps-form__panel p-3 border-radius-xl bg-white js-active"
                                data-animation="FadeIn">
                                <h5 class="font-weight-bolder mb-0">
                                    {{ __('update New Video') }}
                                </h5>

                                <div class="multisteps-form__content">
                                    <div class="row mt-3">
                                        <div class="col-12 col-sm-6">
                                            <label>{{ __('Name Video') }}</label><label class="text-danger">*</label>
                                            <input name="name" class="multisteps-form__input form-control"
                                                type="text" value="{{ $video->name }}">
                                        </div>
                                        <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                            <label>{{ __('url Video') }}</label><label class="text-danger">*</label>
                                            <input name="url" class="multisteps-form__input form-control"
                                                type="text" value="{{ $video->url }}">
                                            
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label>{{ __('Description') }}</label><label class="text-danger">*</label>
                                            <input name="description" class="multisteps-form__input form-control"
                                                type="text" value="{{ $video->description }}">
                                            
                                        </div>

                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label>{{ __('Time') }}</label><label class="text-danger">*</label>
                                            <input name="time" class="multisteps-form__input form-control"
                                                type="time" value="{{ $video->time}}">
                                           
                                        </div>

                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12 col-sm-6">
                                            <label>{{ __('View') }}</label><label class="text-danger">*</label>
                                            <input name="isActive" type="radio" value="1" {{ ($video->isActive== 1 ? "checked" :'' ) }} >
                                           
                                        </div>
                                        <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                            <label>{{ __('isNotActive') }}</label><label class="text-danger">*</label>
                                            <input name="isActive" type="radio" value="0" {{ ($video->isActive== 0 ? "checked" :'' ) }}>
                                            
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="button-row text-left mt-4">
                                <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next" type="submit"
                                    title="Next">تقديم</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection