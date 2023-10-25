@extends('layouts.'.($layout ?? 'primary'))
@section('content')

    <div class="page-header card min-height-250 "@if(!empty($user->cover_photo))
        style="background-image: url('{{PUBLIC_DIR}}/uploads/{{$user->cover_photo}}'); background-position-y: 50%;"
            @endif>
        <span class="mask bg-gradient-dark opacity-6"></span>
    </div>
    <div class="mx-4 mt-n5 overflow-hidden">
        <div class="row gx-4 my-3">

            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1 mt-5">

                    </h5>
                    <p class="mb-0  text-sm">

                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                <div class="nav-wrapper position-relative end-0">
                    <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row  mb-5">
        <div class="col-md-8">
            <div class="card mt-4">

                <div class="card-body">
                        @if($package)
                            <h5 class="fw-bolder mb-4"> الباقة الحالية</h5>
                            <div class="col-12">
                                <ul class="list-group p-0" style="list-style: none">
                                    @if($package->is_active == 0)
                                        <h6 class="bg-danger w-50 p-1 rounded">لم يتم الموافقة عليها من الادمن </h6>
                                    @endif
                                    <li class="list-group-item border-0 pe-0 pt-0 text-sm">
                                        <strong class="text-dark">اسم الباقة :  </strong> <strong class="mx-2">{{$package->subscriptionPlan->name??""}}</strong>
                                    </li>
                                    <li>
                                        <strong class="text-dark">نوع الاشتراك :  </strong> <strong class="mx-2">{{$package->subscription_type??""}}</strong>
                                    </li>
                                    <li>
                                        <strong class="text-dark"> تكلفة الاشتراك :  </strong> <strong class="mx-2">{{$package->price??""}}</strong>
                                    </li>
                                    <li>
                                        <strong class="text-dark">طريقة الدفع :  </strong> <strong class="mx-2">{{$package->payment_type??""}}</strong>
                                    </li>

                                    <li>
                                        <strong class="text-dark">بداية الاشتراك :  </strong> <strong class="mx-2">{{$package->subscription_date_start??""}}</strong>
                                    </li>

                                    <li>
                                        <strong class="text-dark">نهاية الاشتراك :  </strong> <strong class="mx-2">{{$package->subscription_date_end??""}}</strong>
                                    </li>

                                    <li>
                                        @if($showReSubscribe)
                                            <a class="btn btn-primary my-4" href="{{route('packages.details', $plan->id)}}">أعادة الاشتراك</a>
                                        @endif
                                    </li>
                                </ul>
                            </div>

                        @else
                            <h5 class="fw-bolder mb-4">لايوجد باقة حالية</h5>
                        @endif


                </div>
            </div>
        </div>
    </div>
@endsection
