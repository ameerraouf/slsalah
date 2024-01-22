@extends('layouts.'.($layout ?? 'primary'))
@section('content')

    <div class="row">


        <div class="col-12">
            <h4 class="my-2">باقات أخرى </h4>
            <div class="row">
            @foreach($plans as $plan)
                <div class="col-md-4  mb-4 ">
                        <div class="card " style="height: 460px; overflow-y:scroll">
                            <div class="card-header text-center ">
                                @if($plan->active == 0)
                                    <h6 class="bg-danger text-white rounded w-50 mx-auto">معطلة</h6>
                                @endif
                                <h5 class="text-purple opacity-8 text mb-2">{{$plan->name}}</h5>
                                <p>{!! $plan->description !!}</p>
                                <span>
                            <h5 class="font-weight-bolder">
                                {{formatCurrency($plan->price_monthly,getWorkspaceCurrency($settings))}} /
                                <span>
                                    <small class=" text-sm text-warning text-uppercase d-inline">{{__(' month')}}</small>
                                </span>
                            </h5>
                        </span>
                                <h5 class="mt-0">
                                    {{formatCurrency($plan->price_yearly,getWorkspaceCurrency($settings))}} /<span><small
                                                class="text-sm  text-uppercase text-warning">{{__(' year')}}</small></span>
                                </h5>
                            </div>
                            <div class="card-body mx-auto pt-0">

                                @if($plan->features)
                                    @foreach(json_decode($plan->features) as $feature)

                                        <div class="justify-content-start d-flex px-2 py-1">
                                            <div>
                                                <i class="icon icon-shape text-center icon-xs rounded-circle fas fa-check bg-purple-light text-purple text-sm"></i>
                                            </div>
                                            <div class="ps-2">
                                                <span class="text-sm">{{$feature}}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                            <div>
                                @if($plan->active == 1)
                                <a class="btn btn-primary  mt-1 mx-2" href="{{route('packages.details', $plan->id)}}">اشتراك</a>
                                @endif
                            </div>
                        </div>

                </div>

            @endforeach
            </div>

        </div>
        <div class="col-12 mt-5">
            <h4 class="fw-bolder mb-4">باقاتك</h4>
            <div class="row">
            @forelse ($user->subscribes as $key => $package)
            <div class="col-4">
                <div class="card" style="max-height: 430px;min-height: 430px;">

                    <div class="card-body">
                        @if($package->is_active == 0)
                            <h6 class="bg-danger w-100 p-1 rounded">لم يتم الموافقة عليها من الادمن </h6>
                        @endif
                        - {{$key + 1}}


                        <div class="row d-flex flex-wrap px-2 p-2 " >

                            <div class="col-12 p-1 btn-info rounded my-2 ">
                                <strong class="text-dark">اسم الباقة :  </strong> <strong class="mx-2">{{$package->subscriptionPlan->name??""}}</strong>
                            </div>

                            <div class="col-12 p-1 btn-info rounded my-2">
                                <strong class="text-dark">نوع الاشتراك :  </strong> <strong class="mx-2">{{trans("$package->subscription_type")??""}}</strong>
                            </div>
                            <div class="col-12 p-1  btn-info rounded my-2">
                                <strong class="text-dark"> تكلفة الاشتراك :  </strong> <strong class="mx-2">{{$package->price??""}}</strong>
                            </div>
                            <div class="col-12 p-1 btn-info rounded my-2">
                                <strong class="text-dark">طريقة الدفع :  </strong> <strong class="mx-2">{{trans("$package->payment_type")??""}}</strong>
                            </div>
                            <div class="col-12 p-1 btn-info">
                                <strong class="text-dark">بداية الاشتراك :  </strong> <strong class="mx-2">{{$package->subscription_date_start??""}}</strong>
                            </div>

                            <div class="col-12 p-1 btn-info my-2">
                                <strong class="text-dark">انتهاء الاشتراك :  </strong> <strong class="mx-2">{{$package->subscription_date_end??""}}</strong>
                            </div>

                            <div class="col-12">
                                @php
                                    $plan =\App\Models\SubscriptionPlan::query()->find($package->subscription_plan_id);
                                @endphp
                                @if($plan)
                                @if($plan->active == 1)
                                    @if(checkSubscribeIsExpire($package->id))
                                        <a class="btn btn-primary  mt-1" href="{{route('packages.details', $plan->id)}}">اعادة الاشتراك</a>
                                    @else
                                        <button class="btn btn-primary  mt-1" disabled >اعادة الاشتراك</button>
                                    @endif
                                @endif
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            </div>

            @empty
            <h4 class="my-2">غير مشترك فى أى باقة حاليا</h4>
            @endforelse
            </div>
        </div>
    </div>
@endsection
