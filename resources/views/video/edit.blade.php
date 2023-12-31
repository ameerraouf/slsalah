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
                        <form action="{{ route('video.update',$video->id) }}" method="post" class="multisteps-form__form mb-8"  enctype="multipart/form-data">
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
                                            <input name="video_name" class="multisteps-form__input form-control"
                                                type="text" value="{{ $video->name }}">
                                        </div>
                                        <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                            <label>{{ __('url Video') }}</label><label class="text-danger">*</label>
                                            <input name="video_url" class="multisteps-form__input form-control"
                                                type="text" value="{{ $video->url }}">
                                            
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label>{{ __('Description_in_video') }}</label><label class="text-danger">*</label>
                                            <input name="video_description" class="multisteps-form__input form-control"
                                                type="text" value="{{ $video->description }}">
                                            
                                        </div>

                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label>{{ __('video_type') }}</label><label class="text-danger">*</label>
                                            <select name="video_type" class="form-control" >
                                                <option selected disabled>اختر</option>
                                                <option @if($video->video_type == 'study_projects') selected @endif value="study_projects">  فيديوهات تعليمية حول دراسة المشاريع وإعداد الخطط اللازمة باستخدام الأدوات العلمية اللازمة</option>
                                                <option @if($video->video_type == 'financial_planning') selected @endif value="financial_planning"> فيديوهات تعليمية حول التخطيط المالي للمشاريع باستخدام الأدوات العلمية اللازمة</option>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label>{{ __('Time_in_video') }}</label><label class="text-danger">*</label>
                                            <input name="video_time" class="multisteps-form__input form-control"
                                                type="time" value="{{ $video->time}}">
                                           
                                        </div>

                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12 col-sm-6">
                                            <label>{{ __('View') }}</label><label class="text-danger">*</label>
                                            <input name="video_isActive" type="radio" value="1" {{ ($video->isActive== 1 ? "checked" :'' ) }} >
                                           
                                        </div>
                                        <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                            <label>{{ __('isNotActive') }}</label><label class="text-danger">*</label>
                                            <input name="video_isActive" type="radio" value="0" {{ ($video->isActive== 0 ? "checked" :'' ) }}>
                                            
                                        </div>
                                    </div>
                                    <div class="row col-4">
                                        <div class="form-group">
                                            <label for="image">صورة كبرفيو للفيديو : </label>
                                            <input type="file" class="form-control-file" id="image" name="video_image">
                                            <img id="image-preview" src="{{'/uploads/'. $video->image}}" alt="Image Preview" style=" max-width: 100%; height: auto;">
                                        </div>

                                    </div>

                                </div>
                            </div>

                            <div class="button-row text-left mt-4">
                                <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next" type="submit"
                                    title="Next">@lang('add')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
    // Image preview
    $("#image").change(function() {
        readURL(this);
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $("#image-preview").attr("src", e.target.result).show();
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
});
</script>
