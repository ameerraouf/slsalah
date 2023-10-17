@extends('layouts.super-admin-portal')
@section('content')

    <div class="mx-4 mt-n5 overflow-hidden">
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
        <div class="container">
            <div class="row mt-lg-0 mt-4 d-flex">
                @foreach($notifications as $notification)

                    <a href="{{route('admin.subscriptions.details',$notification['data']['subscribe']['id'])}}">
                    <div class="col-12 border border mb-2 p-2 cursor-pointer @if(is_null($notification->read_at)) bg-secondary text-white @endif" onclick="readNotification(this)">

                              <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                              <strong class="mx-1">{{ $notification->data['type'] }} |</strong>
                              <strong class="mx-1">أسم المتسخدم : {{$notification['data']['user']['first_name'] . $notification['data']['user']['last_name'] }} |</strong>
                              <strong>اسم الباقة : <strong>{{$notification->data['plan']? $notification->data['plan']['name']:""}}</strong> |</strong>
                              <strong class="mx-1">تاريخ الاشتراك :{{\Carbon\Carbon::parse($notification['data']['subscribe']['created_at'])->format('Y-m-d m:h:s')}}</strong>
                              <span> -
                                            @if(is_null($notification->read_at))
                                      <span class="mx-2">جديد</span>
                                  @else
                                      <span>مرئي</span>
                                  @endif
                                        </span>

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
        $(element).find('span').text(' - مرئي ');

    var notificationId = $(element).find('input[name="notification_id"]').val();
console.log('the id is '+ notificationId);
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
