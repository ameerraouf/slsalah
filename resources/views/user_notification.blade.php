@extends('layouts.' . ($layout ?? 'primary'))
<style>
    .font-size-12 {
        font-size: 12px;
    }
</style>
@section('content')
    <div class="mt-n5 overflow-hidden">
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
        </div>
    </div>
    <div class="">
        <div class="row mt-lg-0 mt-4 d-flex">
            @forelse ($notifications as $notification)
                @if ($notification['data']['notification_type'] == 'subscription')
                    <a href="{{ route('user.package') }}">
                        <div></div>
                        <div class="d-flex p-2 rounded flex-wrap border align-content-between cursor-pointer @if (is_null($notification->read_at)) bg-secondary text-white @endif"
                            onclick="readNotification(this)">
                            <div class="col-3">
                                <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                <strong class="mx-1 font-size-12">{{ $notification['data']['type'] }} </strong>
                            </div>
                            <div class="col-3">
                                @php
                                    $plan = \App\Models\SubscriptionPlan::find($notification['data']['plan']['id']);
                                @endphp

                                <strong class="font-size-12">اسم الباقة : <strong
                                        class="font-size-12">{{ $plan ? $plan->name : '' }}</strong> </strong>
                            </div>

                            <div class="col-4">
                                <strong class="mx-1 font-size-12">تاريخ ووقت الاشتراك
                                    :{{ \Carbon\Carbon::parse($notification['data']['subscribe']['created_at'])->format('Y-m-d m:h:s') }}</strong>
                            </div>

                            <div class="co-2">
                                <span>
                                    @if (is_null($notification->read_at))
                                        <span class="mx-2 font-size-12">اشعار جديد</span>
                                    @else
                                        <span class="font-size-12">اشعار مرئي</span>
                                    @endif
                                </span>
                            </div>

                        </div>
                    </a>
                @else
                    <a href="{{ route('user.video.show', $notification['data']['video']) }}">
                        <div class="d-flex p-2 rounded flex-wrap border align-content-between cursor-pointer @if (is_null($notification->read_at)) bg-secondary text-white @endif"
                            onclick="readNotification(this)">
                            <div class="col-3">
                                <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                <strong class="mx-1">{{ $notification['data']['type'] }} </strong>
                            </div>

                            {{--                        <strong>اسم الباقة : <strong>{{$notification->data['plan']? $notification->data['plan']['name']:""}}</strong> |</strong> --}}
                            {{--                        <strong class="mx-1">تاريخ الاشتراك :{{\Carbon\Carbon::parse($notification['data']['subscription']['created_at'])->format('Y-m-d m:h:s')}}</strong> --}}
                            <div class="col-3">
                                <span>
                                    @if (is_null($notification->read_at))
                                        <span class="mx-2">اشعار جديد</span>
                                    @else
                                        <span>اشعار مرئي</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </a>
                @endif

            @empty
                <h4 class="my-2"> لا يوجد اشعارات حاليا </h4>
            @endforelse
        </div>
    </div>

@endsection

<script>
    function readNotification(element) {
        $(element).removeClass('bg-secondary');
        $(element).removeClass('text-white');
        $(element).find('span').text('  اشعار مرئي ');

        var notificationId = $(element).find('input[name="notification_id"]').val();
        console.log('the id is ' + notificationId);
        $.ajax({
            url: "/user/notifications/" + notificationId,
            type: 'POST',
            data: {
                notification_id: notificationId
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                var countElement = $('#user_notification_count');
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
