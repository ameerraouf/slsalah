
@extends('frontend.layout')
@section('title','Pricing')
@section('content')
    <section class="">
        <div class="bg-pink-light position-relative">
            <img src="" class="position-absolute start-0 top-md-0 w-100 opacity-6">
            <div class=" postion-relative z-index-2">
                <div class="row mb-3" style="margin-top: -15px">
                    <div class="col-md-8 mx-auto text-center ">
                        <h2 class="text-dark">{{__('package_details')}}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            @if(session()->has('error_message'))
               <div class="alert alert-danger mt-4">{{session()->get('error_message')}}</div>
            @endif
            <div class="row d-flex">
                <div class="col-12">
                    <div class="col-4">
                        <strong class="btn btn-info w-100 text-start"><span class="mx-1"> أسم الباقة : </span>  {{$package->name}}</strong>
                    </div>
                </div>
                <div class="col-12">
                    <div class="col-4">
                        <strong class="btn btn-info w-100 text-start"><span class="mx-1"> الحد الأقصى للمستخدمين المسموح بهم : </span>  {{$package->maximum_allowed_users}}</strong>
                    </div>
                </div>
                <div class="col-12">
                    <div class="col-4">
                        <strong class="btn btn-info w-100 text-start"><span class="mx-1"> الحد الأقصى لحجم الملفات المرفوعه (كيلوبايت) : </span>  {{$package->max_file_upload_size}}</strong>
                    </div>
                </div>
                <div class="col-12">
                    <div class="col-4">
                        <strong class="btn btn-info w-100 text-start"><span class="mx-1"> نوع الملفات المسموح بها  : </span>  {{$package->file_space_limit}}</strong>
                    </div>

                </div>

<hr>
                <div class="d-flex mb-5">
                    <div class="col-3">
                        <a class="btn btn-primary p-3" href="{{route('paypalProcessTransaction', ['package' => $package->id, 'type' => 'monthly'])}}">باي بال (اشتراك شهري)</a>
                    </div>
                    <div class="col-3 ">
                        <a class="btn btn-primary p-3" href="{{route('paypalProcessTransaction', ['package' => $package->id, 'type' => 'yearly'])}}">باي بال (اشتراك سنوي)</a>
                    </div>
                    <div class="col-4">
                        <a class="btn btn-primary p-3" href="{{route('click_pay', ['package' => $package->id, 'type' => 'monthly','u' => auth()->id()])}}">كليك باي (اشتراك شهري)</a>
                    </div>
                    <div class="col-4">
                        <a class="btn btn-primary p-3" href="{{route('click_pay', ['package' => $package->id, 'type' => 'yearly','u' => auth()->id()])}}">كليك باي (اشتراك سنوي)</a>
                    </div>
                </div>
            </div>
        </div>
@endsection













