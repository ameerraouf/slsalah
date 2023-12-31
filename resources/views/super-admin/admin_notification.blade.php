@extends('layouts.super-admin-portal')
<style>
    .font-size-12{
        font-size: 12px;
    }
</style>
@section('content')

    <div class=" mt-n5 overflow-hidden">
        <div class="row gx-4 my-3">

            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1 mt-5">الاشعارات</h5>
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
        </di>
    </div>
        <div class="">
            <div class="mt-lg-0 mt-4">
                @foreach($notifications as $notification)
                    <a href="{{route('admin.subscriptions.details',$notification['data']['subscribe']['id'])}}">
                        <div class="d-flex p-2 rounded flex-wrap border align-content-between cursor-pointer @if(is_null($notification->read_at)) bg-secondary text-white @endif" onclick="readNotification(this)">
                            <div class="col-2  ">
                                <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                <strong class="font-size-12">{{ $notification->data['type'] }}</strong>
                            </div>
                            <div class="col-2 ">
                                @php
                                    $user = \App\Models\User::find($notification['data']['user']['id']);
                                    $plan = \App\Models\SubscriptionPlan::find($notification['data']['plan']['id']);
                                @endphp
                                <strong class="font-size-12">اسم المستخدم: {{$user ? $user->first_name . " " . $user->last_name : ""}}</strong>
                            </div>
                            <div class="col-3 ">
                                <strong class="font-size-12">اسم الباقة: <strong class="font-size-12">{{$plan ? $plan->name : ""}}</strong></strong>
                            </div>
                            <div class="col-4 ">
                                <strong class="mx-1 font-size-12">تاريخ ووقت الاشتراك: {{\Carbon\Carbon::parse($notification['data']['subscribe']['created_at'])->format('Y-m-d H:i:s')}}</strong>
                            </div>
                            <div class="col-1 ">
                                <span>
                                    @if(is_null($notification->read_at))
                                        <span class="mx-2 font-size-12 ">اشعار جديد</span>
                                    @else
                                        <span class="font-size-12">اشعار مرئي</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

@endsection

<script>
    function readNotification(element) {
        $(element).removeClass('bg-secondary');
        $(element).removeClass('text-white');
        $(element).find('span').text(' - اشعار مرئي ');

    var notificationId = $(element).find('input[name="notification_id"]').val();

    $.ajax({
        url: "/admin/notifications/"+ notificationId,
        type: 'POST',
        data: { notification_id: notificationId },
          headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  },
        success: function(response) {
             var countElement = $('#admin_notification_count');
            var count = parseInt(countElement.text());
            if (!isNaN(count) && count > 0) {
                countElement.text(count - 1);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error marking notification as read:', error);
        }
    });
    }
</script>
