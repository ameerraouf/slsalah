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
                <div class="card-body px-0 pt-0 pb-2">
                    <div><span class="mx-3  d-inline-block" style="width: 100px">اسم المستخدم</span>: {{$plan->user->first_name . ' '. $plan->user->last_name}}</div>
                    <br>
                    <div><span class="mx-3  d-inline-block" style="width: 100px">اسم الباقة</span>: {{$plan->subscriptionPlan->name}}</div>
                    <br>
                    <div><span class="mx-3  d-inline-block" style="width: 100px">نوع الاشتراك</span>: {{$plan->subscription_type}}</div>
                    <br>
                    <div><span class="mx-3  d-inline-block" style="width: 100px">السعر</span>: {{$plan->price}}</div>
                    <br>
                    <div><span class="mx-3  d-inline-block" style="width: 100px">وسيله الدفع</span>: {{$plan->payment_type}}</div>
                    <br>
                    <div><span class="mx-3  d-inline-block" style="width: 100px">بدايه الاشتراك</span>: {{$plan->subscription_date_start}}</div>
                    <br>
                    <div><span class="mx-3  d-inline-block" style="width: 100px">نهاية الاشتراك</span>: {{$plan->subscription_date_end}}</div>
                    <br>
                    <div><span class="mx-3  d-inline-block" style="width: 100px">حالة الاشتراك
                        </span>:
                        @if($plan->is_subscription_end == 0)
                            <span class="badge badge-sm bg-success-light text-success">ساري</span>
                        @else
                            <span class="badge badge-sm bg-pink-light text-danger">منتهي</span>
                        @endif
                    </div>
                    <br>
                    <div><span class="mx-3  d-inline-block" style="width: 100px">اسم البنك</span>: {{$plan->bank_name}}</div>
                    <br>
                    <div><span class="mx-3  d-inline-block" style="width: 100px">رقم التحويل</span>: {{$plan->bank_name}}</div>
                    <br>
                    <div>
                        <span class="mx-3  d-inline-block" style="width: 100px">صوره التحويل</span> :
                        @if($plan->bank_name)
                        <a target="_blank" href="{{'/uploads/' . $plan->image_bank_transfer}} ">
                            <strong class="bg-danger text-white p-2 rounded">الصوره</strong>
                        </a>
                        @endif
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
