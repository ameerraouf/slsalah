
@extends('frontend.layout')
@section('title','Pricing')
@section('content')
    <section class="">
        <div class="bg-pink-light position-relative">
            <img src="" class="position-absolute start-0 top-md-0 w-100 opacity-6">
            <div class="pb-lg-9 pb-7 pt-7 postion-relative z-index-2">
                <div class="row mt-4">
                    <div class="col-md-8 mx-auto text-center mt-4">
                        <h2 class="text-dark">{{__('Plans & Pricing')}}</h2>

                    </div>
                </div>
            </div>
        </div>
        <div class="">
            <div class="mt-sm-n5 mt-n4 mb-10">
                <div class="container">
                    <div class="row">
                        @foreach($plans as $plan)
                            <div class="col-md-4 text-white  mb-4 " style="min-height: 500px;max-height: 500px">
                                <div class="card bg-info h-100">
                                    <div class="card-header mt-4 bg-info text-center ">
                                        <h4 class="text-white text mb-2" style="margin-bottom: 5px!important;">{{$plan->name}}</h4>
                                        <p>{!! $plan->description !!}</p>
                                        <span>
                                            <h4 class="font-weight-bolder text-white">
                                                {{formatCurrency($plan->price_monthly,getWorkspaceCurrency($super_settings))}} /
                                                <span>
                                                    <small class=" text-sm text-warning text-uppercase d-inline mx-1">{{__(' month')}}</small>
                                                </span>
                                            </h4>
                                        </span>


                                        <h4 class="mt-0  text-white">
                                            {{formatCurrency($plan->price_yearly,getWorkspaceCurrency($super_settings))}} /
                                            <span>
                                                <small class="text-sm  text-uppercase text-warning d-inline mx-1">{{__(' year')}}</small>
                                            </span>
                                        </h4>

                                    </div>
                                    <div class="card-body mx-auto pt-0" >
                                        @if($plan->features)

                                            @foreach(json_decode($plan->features) as $feature)

                                                <div class=" justify-content-start d-flex px-2">
                                                    <div>
                                                        <i class="fas fa-check text-white text-sm"></i>
                                                    </div>
                                                    <div class="ps-2">
                                                        <span class="text-sm">{{$feature}}</span>
                                                    </div>
                                                </div>


                                            @endforeach


                                        @endif
                                    </div>


                                    @if(isUserSubscribeInPlan(auth()->id(), $plan->id))
{{--                                        if expire--}}
                                    @php
                                        $subscribe = \App\Models\Subscribe::where('subscription_plan_id',$plan->id)->where('user_id', auth()->id())->latest()->first()->id;

                                    @endphp
                                        @if(checkSubscribeIsExpire($subscribe??0))
                                            <a href="{{route('packages.details', $plan->id)}}" class="btn btn-primary w-75 mx-auto" >أعادة الاشتراك</a>
                                          @else
                                            <button class="btn btn-primary w-75 mx-auto">أنت بالفعل مشترك فى هذه الباقة</button>
                                        @endif

                                    @else
                                        @if($plan->active == 1)
                                        <div class="card-footer text-center pt-0">
                                            <a href="{{route('packages.details',$plan->id)}}" type="button"
                                               class="btn  btn-white mb-0 ">{{__('اشتراك')}}</a>
                                        </div>
                                        @endif
                                    @endif

                                </div>
                            </div>

                        @endforeach

                    </div>
                </div>
            </div>

        </div>










@endsection













