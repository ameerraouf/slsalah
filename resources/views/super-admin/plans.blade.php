@extends('layouts.super-admin-portal')
@section('content')
    <div class="row mb-2">
        <div class="col">
            <h5 class=" text-secondary fw-bolder">
                {{__('Plans List')}}
            </h5>
            <p class="text-muted">{{__('Create, edit or delete the plans')}}</p>
        </div>
        <div class="col text-end">
            <a href="/subscription-plan" type="button" class="btn btn-info">{{__('Create Plan')}}</a>
        </div>
    </div>

    <div class="row">
        @foreach($plans as $plan)
            <div class="col-md-4  mb-4 ">
                <div class="card " style="height: 460px; overflow-y:hidden">
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

                            <div class="justify-content-start d-flex px-2 py-1">
                                <div class="ps-2 font-weight-bolder opacity-8" style="font-size: 12px">
                                    عدد المشتركين في الباقة ( <span class="text-sm">{{ numberOfSubscriptionInPlan($plan->id) }}</span> )
                                </div>
                            </div>
                    </div>
                    <div class="card-footer pt-0 d-flex justify-content-center">
                        <a href="/subscription-plan?id={{$plan->id}}" type="button" style="position: absolute;bottom: 0px;"
                           class="btn btn-info mt-3 btn-md ">{{__('Edit')}}</a>
{{--                        <a href="/delete/subscription-plan/{{$plan->id}}" type="button"--}}
{{--                           class="btn btn-warning btn-md mt-3">{{__('Delete')}}</a>--}}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection
