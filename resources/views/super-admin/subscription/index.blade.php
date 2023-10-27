@extends('layouts.super-admin-portal')
@section('content')

    <div class=" row">
        <div class="col">
            <h5 class="mb-2 text-secondary fw-bolder">
                {{__('Subscription')}}
            </h5>

        </div>
    </div>

    <div class="row">

        <div class="col-12">

            <div class="card card-body mb-4">
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="cloudonex_table">
                            <div >
                                <form action="{{route('all_subscriptions')}}">
                                    <div class="my-2 d-flex flex-wrap">
                                    <div class="col-3">
                                        <label>حالة الاشتراك : </label>
                                        <select class="px-2 " name="is_subscription_end">
                                            <option disabled selected> اختر</option>
                                            <option value="false" {{ request()->has('is_subscription_end') && request()->get('is_subscription_end') == 'false' ? 'selected' : '' }}>ساري</option>
                                            <option value="true" {{ request()->has('is_subscription_end') && request()->get('is_subscription_end') == 'true' ? 'selected' : '' }}>منتهي</option>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <label>الباقة : </label>
                                        <select class="px-2 " name="subscription_plan_id">
                                            <option disabled selected> اختر</option>
                                            @foreach(\App\Models\SubscriptionPlan::query()->get() as $plan)
                                                <option @if($plan->id == request()->subscription_plan_id) selected @endif value="{{$plan->id}}" >{{$plan->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <label>نوع الاشتراك : </label>
                                        <select class="px-2 " name="subscription_type">
                                            <option disabled selected> اختر</option>
                                            <option value="monthly" {{ request()->has('subscription_type') && request()->get('subscription_type') == 'monthly' ? 'selected' : '' }}>شهري</option>
                                            <option value="yearly" {{ request()->has('subscription_type') && request()->get('subscription_type') == 'yearly' ? 'selected' : '' }}>سنوي</option>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-primary">فلتر</button>
                                        <button type="button" class="btn btn-info" onclick="window.location.href='/subscriptions-all'">أعادة تعيين</button>
                                    </div>
                                    </div>
                                </form>

                            </div>
                            <hr>
                            <thead>

                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('username')}}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('plan_name')}}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">نوع الاشتراك</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('amount')}}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('payment method')}}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('Subscription date')}}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">حالة الاشتراك</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('Transfer number')}}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">الأجراء</th>
                            </tr>

                            <tbody>
                            @foreach($workspaces as $key=> $workspace)

                                <tr>
                                    <td class="text-center">
                                        {{$key}}
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{isset($workspace->user) ? $workspace->user->first_name . ' ' . $workspace->user->last_name : ''}}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{isset($workspace->subscriptionPlan) ? $workspace->subscriptionPlan->name : ''}}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{trans("$workspace->subscription_type")}}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{$workspace->price}}</p>
                                    </td>

                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{trans("$workspace->payment_type")}}</p>
                                    </td>

                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ \Carbon\Carbon::parse($workspace->subscription_start_date)->format('Y-m-d')}}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            @if($workspace->is_subscription_end == 0)
                                                <span class="badge badge-sm bg-success-light text-success">ساري</span>
                                            @else
                                                <span class="badge badge-sm bg-pink-light text-danger">منتهي</span>
                                            @endif
                                        </p>
                                    </td>

                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            @if($workspace->number_of_transfer)
                                                {{$workspace->number_of_transfer}}
                                                @else
                                                <span>---</span>
                                            @endif

                                        </p>
                                    </td>
                                    <td>
                                        @if($workspace->is_active == 0)
                                            <form action="{{route('admin.active_subscription', $workspace->id)}}" method="post">
                                                @csrf
                                                @method('post')
                                                <button type="submit" class="btn btn-primary">تفعيل الاشتراك</button>
                                            </form>
                                        @endif
                                        <a class="btn btn-info" href="{{route('admin.subscriptions.details', $workspace->id)}}">عرض التفاصيل</a>
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>

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
                },
            });
         $('input[type="search"]').on('input', function() {
            var searchTerm = $(this).val().toLowerCase();
            var rowCount = 0;

            $('#cloudonex_table tbody tr').each(function(index) {
              var rowData = $(this).text().toLowerCase();
              if (rowData.includes(searchTerm) || searchTerm === '') {
                rowCount++;
                $(this).find('td:first-child').text(rowCount);
              }
            });

            console.log(rowCount);
          });
        });

    </script>

@endsection
