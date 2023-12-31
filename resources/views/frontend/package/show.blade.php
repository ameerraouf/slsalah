
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


                @if(isUserSubscribeInPlan(auth()->id(), $package->id))
                    @php
                        $subscribe = \App\Models\Subscribe::query()->where('subscription_plan_id', $package->id)->where('user_id', auth()->id())->first();
                    @endphp
                    @if(checkSubscribeIsExpire($subscribe->id))
                        <h4 class="mt-4">وسائل الدفع</h4>
                        <div class="d-flex mb-5">
                            <div class="col-3">
                                <a class="btn btn-primary" href="{{route('user.pay_online', $package->id)}}"> الدفع أونلاين </a>
                            </div>
                            <div class="col-3">
                                <a class="btn btn-primary" href="{{route('user.pay_bank', $package->id)}}">حوالة بنكية</a>
                            </div>
                        </div>
                    @else
                        <div class="btn btn-primary w-50 mx-auto">انت مشترك بالفعل في هذه الباقة</div>
                    @endif

                @else
                    <h4 class="mt-4">وسائل الدفع</h4>
                    <div class="d-flex mb-5">
                        <div class="col-3">
                            <a class="btn btn-primary" href="{{route('user.pay_online', $package->id)}}"> الدفع أونلاين </a>
                        </div>
                        <div class="col-3">
                            <a class="btn btn-primary" href="{{route('user.pay_bank', $package->id)}}">حوالة بنكية</a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
@endsection













