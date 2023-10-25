@extends('layouts.super-admin-portal')
@section('content')

    <div class=" row">
        <div class="col">
            <h5 class="mb-4 text-secondary fw-bolder">
               تفاصيل الاشتراك
            </h5>

        </div>
    </div>

    <div class="row">

        <div class="col-12">
            <div class="card card-body mb-4">
                <div class="card-body px-0 pt-0 pb-2 align-center">
                   <div class="row border rounded mb-2">
                       <div class="col-3  p-1 rounded my-2 border-right-0" >
                           <span class="mx-3  " >اسم المستخدم</span>: {{$plan->user->first_name . ' '. $plan->user->last_name}}

                       </div>

                       <div class="col-3  p-1 rounded  my-2">
                           <span class="mx-3  " >اسم الباقة</span>: {{$plan->subscriptionPlan->name}}
                       </div>

                       <div class="col-3  p-1 rounded  my-2">
                           <span class="mx-3">نوع الاشتراك</span>: {{trans("$plan->subscription_type")}}
                       </div>
                       <div class="col-3  p-1 rounded  my-2">
                           <span class="mx-3">المبلغ</span>: {{$plan->price}}
                       </div>

                   </div>

                   <div class="row border rounded mb-2">
                       <div class="col-3  p-1 rounded">
                           <span class="mx-3 ">طريقة الدفع</span>: {{trans("$plan->payment_type")}}
                       </div>

                       <div class="col-3  p-1 rounded ">
                           <span class="mx-3">بدايه الاشتراك</span>: {{$plan->subscription_date_start}}
                       </div>
                       <div class="col-3  p-1 rounded">
                           <span class="mx-3">نهاية الاشتراك</span>: {{$plan->subscription_date_end}}
                       </div>

                       <div class="col-3 p-1 rounded">
                           <span class="mx-3 ">حالة الاشتراك</span>:
                           @if($workspace->is_subscription_end == 0)
                               <span class="badge badge-sm bg-success-light text-success">ساري</span>
                           @else
                               <span class="badge badge-sm bg-pink-light text-danger">منتهي</span>
                           @endif
                       </div>
                   </div>
                <div class="row rounded border mb-2">

                    <div class="col-3 p-1">
                        <span class="mx-3" >اسم البنك</span>: {{$plan->bank_name}}
                    </div>
                    <div class="col-3 p-1">
                        <span class="mx-3  d-inline-block" style="width: 100px">رقم التحويل</span>: {{$plan->bank_name}}
                    </div>
                    <div class="col-3 p-1">
                        <span class="mx-3" >صوره التحويل</span> :
                        @if($plan->bank_name)
                            <a target="_blank" href="{{'/uploads/' . $plan->image_bank_transfer}} ">
                                <strong class="bg-danger text-white px-2 rounded">الصوره</strong>
                            </a>
                        @endif
                    </div>
                </div>

                </div>
            </div>
        </div>
    </div>


@endsection
@section('script')
    <script>
        "use strict";
        $(document).ready(function () {
            $('#cloudonex_table').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json'
                }
            });

        });

    </script>
@endsection
