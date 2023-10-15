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
        <div class="col-md-4">
            <div class="card mt-4">

                <div class="card-body">

                    <h5 class="fw-bolder mb-4">الباقات الحاليه</h5>

                    @foreach($subscribes as $subscribe)
                        <div class="col-12">
                            <ul class="list-group p-0" style="list-style: none">
                                <li class="list-group-item border-0 pe-0 pt-0 text-sm">
                                    <strong class="text-dark">اسم الباقة :  </strong> <strong class="mx-2">{{$subscribe->subscriptionPlan->name}}</strong>
                                </li>
                                <li>
                                    <strong class="text-dark">نوع الاشتراك :  </strong> <strong class="mx-2">{{$subscribe->subscription_type}}</strong>
                                </li>
                                <li>
                                    <strong class="text-dark"> تكلفة الاشتراك :  </strong> <strong class="mx-2">{{$subscribe->price}}</strong>
                                </li>
                                <li>
                                    <strong class="text-dark">طريقة الدفع :  </strong> <strong class="mx-2">{{$subscribe->payment_type}}</strong>
                                </li>

                                <li>
                                    <strong class="text-dark">بداية الاشتراك :  </strong> <strong class="mx-2">{{$subscribe->subscription_date_start}}</strong>
                                </li>

                                <li>
                                    <strong class="text-dark">نهاية الاشتراك :  </strong> <strong class="mx-2">{{$subscribe->subscription_date_end}}</strong>
                                </li>
                            </ul>
                        </div>
                        <hr>
                    @endforeach

                </div>
            </div>
        </div>

        <div class="col-md-8 mt-lg-0 mt-4">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="list-unstyled">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
           <div class="row">

                   @foreach($plans as $plan)
                   <div class="col-6 text-center my-2">
                           <div class="card " style="height: 460px; overflow-y:scroll">
                               <div class="card-header text-center ">
                                   <h5 class="text-purple opacity-8 text mb-2">{{$plan->name}}
                                       @if(isUserSubscribeInPlan(1, $plan->id))
                                           <span class="bg-danger text-white px-3 rounded">مشترك</span>
                                       @endif
                                   </h5>
                                   <p>{!! $plan->description !!}</p>
                                   <span>
                            <h4 class="font-weight-bolder">
                           {{formatCurrency($plan->price_monthly,getWorkspaceCurrency($settings))}} /<span><small
                                            class=" text-sm text-warning text-uppercase">{{__(' month')}}</small></span>
                            </h4>
                        </span>
                                   <h4 class="mt-0">
                                       {{formatCurrency($plan->price_yearly,getWorkspaceCurrency($settings))}} /<span><small
                                                   class="text-sm  text-uppercase text-warning">{{__(' year')}}</small></span>
                                   </h4>
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
                               <div class="card-footer pt-0">
                                   @if($plan->active)
                                       @if(isUserSubscribeInPlan(1, $plan->id))
                                           <a href="/packages/{{$plan->id}}" target="_blank" type="button"
                                              class="btn btn-info mt-3 btn-md ">اعادة الاشتراك الاشتراك</a>
                                       @else
                                           <a href="/packages/{{$plan->id}}" type="button" target="_blank"
                                              class="btn btn-info mt-3 btn-md ">اشتراك</a>
                                       @endif
                                   @endif
                               </div>
                           </div>
                   </div>
                   @endforeach

           </div>

        </div>
    </div>
@endsection
