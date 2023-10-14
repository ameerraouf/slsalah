
@extends('frontend.layout')
@section('title','Pricing')
@section('content')
    <section class="">
        <div class="bg-pink-light position-relative">
            <img src="" class="position-absolute start-0 top-md-0 w-100 opacity-6">
            <div class="pb-lg-9 pb-7 pt-7 postion-relative z-index-2">
                <div class="row mt-4">
                    <div class="col-md-8 mx-auto text-center mt-4">
                        <h2 class="text-dark">{{__('package_details')}}</h2>

                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row d-flex">
                <div class=""> <span> أسم الباقة : </span>  {{$package->name}}</div>
                <div class=""> <span> الحد الأقصى للمستخدمين المسموح بهم : </span>  {{$package->maximum_allowed_users}}</div>
                <div class=""> <span> الحد الأقصى لحجم الملفات المرفوعه (كيلوبايت) : </span>  {{$package->max_file_upload_size}}</div>
                <div class=""> <span> حد مساحة الملف (ميجابايت) : </span>  {{$package->file_space_limit}}</div>
                <div class=""> <span> حد مساحة الملف (ميجابايت) : </span>  {{$package->file_space_limit}}</div>
                <div class=""> <span> حد مساحة الملف (ميجابايت) : </span>  {{$package->file_space_limit}}</div>

            </div>

        </div>










@endsection













