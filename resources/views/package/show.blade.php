@extends('layouts.'.($layout ?? 'primary'))
@section('content')

    <div class="row">
        <h4 class="fw-bolder mb-4">باقاتك</h4>
        @foreach($user->subscribes as $key => $package)
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                                    - {{$key + 1}}
                        @if($package->is_active == 0)
                            <h6 class="bg-danger w-50 p-1 rounded">لم يتم الموافقة عليها من الادمن </h6>
                        @endif
                                    <div class="row d-flex flex-wrap px-2 p-2 " >

                                            <div class="col-5 btn-info rounded my-2 ">
                                                <strong class="text-dark">اسم الباقة :  </strong> <strong class="mx-2">{{$package->subscriptionPlan->name??""}}</strong>
                                            </div>

                                            <div class="col-6 mx-2 btn-info rounded my-2">
                                                <strong class="text-dark">نوع الاشتراك :  </strong> <strong class="mx-2">{{trans("$package->subscription_type")??""}}</strong>
                                            </div>
                                            <div class="col-5  btn-info rounded my-2">
                                                <strong class="text-dark"> تكلفة الاشتراك :  </strong> <strong class="mx-2">{{$package->price??""}}</strong>
                                            </div>
                                            <div class="col-6 mx-2 btn-info rounded my-2">
                                                <strong class="text-dark">طريقة الدفع :  </strong> <strong class="mx-2">{{trans("$package->payment_type")??""}}</strong>
                                            </div>
                                            <div class="col-5 btn-info">
                                                <strong class="text-dark">بداية الاشتراك :  </strong> <strong class="mx-2">{{$package->subscription_date_start??""}}</strong>
                                            </div>

                                            <div class="col-6 mx-2 btn-info">
                                                <strong class="text-dark">انتهاء الاشتراك :  </strong> <strong class="mx-2">{{$package->subscription_date_end??""}}</strong>
                                            </div>



                                        <div class="col-3">
                                            @if(\App\Models\SubscriptionPlan::query()->find($package->subscription_plan_id)->active != 0)
                                                @if(checkSubscribeIsExpire($package->id))
                                                    <a class="btn btn-primary my-4" href="{{route('packages.details', $plan->id)}}">أعادة الاشتراك</a>
                                                @endif
                                            @endif

                                        </div>
                                    </div>
                    </div>
                </div>
                <hr>
            </div>

        @endforeach
    </div>
@endsection
