
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
                <div class=""> <span> أسم الباقة : </span>  {{$package->name}}</div>
                <div class=""> <span> الحد الأقصى للمستخدمين المسموح بهم : </span>  {{$package->maximum_allowed_users}}</div>
                <div class=""> <span> الحد الأقصى لحجم الملفات المرفوعه (كيلوبايت) : </span>  {{$package->max_file_upload_size}}</div>
                <div class=""> <span> حد مساحة الملف (ميجابايت) : </span>  {{$package->file_space_limit}}</div>
{{--                <div class="mt-4">--}}
{{--                    <h4>نوع الاشتراك</h4>--}}
{{--                    <div class="d-flex">--}}
{{--                        <div class="col-2">--}}
{{--                            <input type="radio" name="subscribe" value="{{$package->price_monthly}}"><span class="mx-2">{{$package->price_monthly}}</span>--}}
{{--                            <span>شهري</span>--}}
{{--                        </div>--}}
{{--                        <div class="col-2">--}}
{{--                            <input type="radio" name="subscribe" value="{{$package->price_yearly}}"><span class="mx-2">{{$package->price_yearly}}</span>--}}
{{--                            <span>سنوي</span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <h4 class="mt-4">وسائل الدفع</h4>
                <div class="d-flex mb-5">
                    <div class="col-3">
                        <a class="text-info" href="{{route('paypalProcessTransaction', ['package' => $package->id, 'type' => 'monthly'])}}">Paypal (اشتراك شهري)</a>
                    </div>
                    <div class="col-3">
                        <a class="text-info" href="{{route('paypalProcessTransaction', ['package' => $package->id, 'type' => 'yearly'])}}">Paypal (اشتراك سنوي)</a>
                    </div>
                    <div class="col-4">
                        <a class="text-info" href="{{route('click_pay', ['package' => $package->id, 'type' => 'monthly'])}}">كليك باي (اشتراك شهري)</a>
                    </div>
                    <div class="col-4">
                        <a class="text-info" href="{{route('click_pay', ['package' => $package->id, 'type' => 'yearly'])}}">كليك باي (اشتراك سنوي)</a>
                    </div>
                </div>
                <div class="col-12">
                    @if ($errors->any())
                        <div class="alert bg-pink-light text-danger">
                            <ul class="list-unstyled">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <h5>حواله بنكية</h5>
                    <form method="post" action="{{route('user.transfer_bank')}}" enctype="multipart/form-data">
                        @csrf
                        @method('post')
                        <div class="row mb-3">
                            <div class="col-12">
                                <input hidden name="plan_id" value="{{$package->id}}">
                                <div class="col-6">
                                       <label>نوع الاشتراك </label>
                                       <input type="radio"  name="subscription_type" value="monthly">    <strong>شهري</strong>
                                       <strong class="mx-3"></strong>
                                       <input type="radio" name="subscription_type" value="yearly"> <strong >سنوي</strong>
                                </div>
                                <div class="col-6 mt-4">
                                    <label>رقم التحويل</label>
                                    <input class="p-2" name="number_of_transfer" type="text" placeholder="رقم التحويل" required value="{{old('number_of_transfer')??''}}">
                                </div>
                                <div class="col-6 my-2">
                                    <label>اسم البنك</label>
                                    <input class="p-2" type="text" name="bank_name" placeholder="اسم البنك" required value="{{old('bank_name')??""}}">
                                </div>
                                <div class="col-6">
                                    <label>مرفق الحواله</label>
                                    <input type="file" name="image_bank_transfer" accept="image/*" required>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">ادفع</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>










@endsection













