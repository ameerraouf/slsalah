@extends('layouts.'.($layout ?? 'primary'))
@section('content')
        <style>
            .messages-container{
                overflow: hidden;
                padding: 10px;
                overflow-y: scroll;
                max-height: 350px;
                min-height: 350px;
            }
            .custom-file-upload {
                display: block;
                padding: 6px 12px;
                cursor: pointer;
                background-color: #e9ecef;
                color: #333;
                border: 1px solid #ced4da;
                border-radius: 4px;
            }
            .custom-file-upload i {
                margin-right: 5px;
            }

        </style>
        <div class="container mt-5">
            <h3>جلسات دعم ومساعدة من قبل مختصين واستشاريين في مجال تخطيط وتنفيذ المشاريع</h3>

            <div class="row">
                <div class="col-8">
                    <div id="chat-messages" class="mt-4 messages-container">
                        @foreach($messages as $message)
                            @if($message->sender_id == auth()->id())
                                <div class="d-flex align-items-center my-4">
                                    <div style="height: 40px;width: 40px">
                                        <img class="rounded-circle h-100 w-100" src="{{$userPhoto}}">
                                    </div>
                                    <div class="align-items-center mx-1">
                                        <span class="d-block text-start">{{$message->message}}</span>
                                        @if($message->file)
                                            <span class="d-block">
                                    <a href="{{$message->file}}" target="_blank" class="btn-primary px-2 rounded text-start">المرفق</a>
                                </span>

                                        @endif
                                        <span class="d-block">{{$message->created_at}}</span>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex align-items-center my-4 float-end mx-4 ">
                                    <div class="align-items-center mx-1">
                                        <span class="d-block text-end">{{$message->message}}</span>
                                        @if($message->file)
                                            <span class="d-block text-end">
                                    <a href="{{$message->file}}" target="_blank" class="text-end btn-primary px-2 rounded ">المرفق</a>
                                    </span>
                                        @endif
                                        <span class="d-block">{{$message->created_at}}</span>
                                    </div>
                                    <div style="height: 40px;width: 40px">
                                        <img class="rounded-circle h-100 w-100" src="{{$adminPhoto}}">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            @endif
                        @endforeach
                    </div>
                    @if(!$chatClosed)
                        <form id="chat-form" class="mt-1">
                            <input type="text" id="message-input" class="form-control" placeholder=" الرسالة">
                            <input type="file" id="message-file" class="form-control my-2" style="display: none;">
                            <label for="message-file" class="custom-file-upload my-2">
                                <i class="fas fa-cloud-upload-alt"></i> ارفاق ملف
                            </label>
                            <div id="file-preview" style="overflow: hidden"></div>
                            <button type="submit" class="btn btn-primary mt-2">ارسال</button>

                        </form>
                    @endif

                </div>
                <div class="col-4">
                    <h4>المحادثات السابقة</h4>
                    @foreach($chats as $chat)

                        <div class="d-flex align-items-center border-top pt-2 cursor-pointer" id="{{$chat->chat_id}}">
                            <div style="height: 40px;width: 40px" class="mx-3">
                                <img class="rounded-circle h-100 w-100" src="{{$chat->receiver->photo? url('uploads/' . $chat->receiver->photo): url('/'. env('DEFAULT_PHOTO')??"")}}">
                            </div>
                            <div class="align-items-center mx-1">
                                <span class="d-block text-start">{{$chat->receiver->first_name . ' '. $chat->receiver->last_name}}</span>
                            </div>
                            <div class="align-items-center ">
                                <span class="text-danger mx-3" id="count{{$chat->chat_id}}"></span>
                            </div>
                        </div>
                        <hr>
                    @endforeach
                </div>
            </div>
        </div>


    @endsection

    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
           $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
        });
        Pusher.logToConsole = true;

        var pusher = new Pusher('{{env('PUSHER_APP_KEY')}}', {
        cluster: 'eu',
        encrypted: true
    });


    var channel = pusher.subscribe('chat-channel');

    channel.bind('new-message', function(data) {
            if(data.message.receiver_id == {{auth()->id()}}) {
                receiver(data)
            }
         });
         var channelReload = pusher.subscribe('reload-page');
          channelReload.bind('reload-page', function(data) {
                location.reload();
         });

         $(document).ready(function() {
             $('#chat-form').submit(function(e) {
                 e.preventDefault();

                     var message = $('#message-input').val();
            var file = $('#message-file')[0].files[0]; // Get the file from the input

            var formData = new FormData(); // Create a FormData object
            formData.append('message', message); // Append the message to the FormData
            formData.append('file', file); // Append the file to the FormData

            formData.append('user_id', sessionStorage.getItem('chat_id'));

             $.ajaxSetup({
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 }
             });


                     // Send the message to the backend
                     $.ajax({
                         url: '/user/chat',
                         type: 'POST',
                       data: formData, // Use the FormData object as the data
                        processData: false, // Prevent jQuery from converting the data into a query string
                        contentType: false,
                         success: function(response) {

                             // Clear the input field
                             var data = response.data;

                             var message = data.message;
                             var timestamp = data.created_at;
                            var clearFix = $('<div>').addClass('clearfix');
                            var messageContainer = $('<div>').addClass('d-flex align-items-center my-2');
                            var avatarContainer = $('<div>').css({ height: '40px', width: '40px' });
                            var avatarImage = $('<img>').addClass('rounded-circle h-100 w-100').attr('src',"{{$userPhoto}}" );
                            var contentContainer = $('<div>').addClass('align-items-center mx-1');

                            var contentText = $('<span>').addClass('d-block text-start').text(message);
                            var timestampText = $('<span>').addClass('d-block text-start').text(timestamp);

                            $('#chat-messages').append(clearFix);
                            contentContainer.append(contentText);
                            if (data.file != null) {
                                 var fileLink = $('<a>').attr('href', data.file).attr('target', '_blank').addClass('btn-primary px-2 rounded d-block w-50').text('المرفق');
                             }
                             if (fileLink) {
                               contentContainer.append(fileLink);
                            }
                            contentContainer.append(timestampText);

                            messageContainer.append(avatarContainer);
                            avatarContainer.append(avatarImage);
                            messageContainer.append(contentContainer);

                            $('#chat-messages').append(messageContainer);


                             $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
                             $('#message-input').val('');
                              $('#message-file').val('');
                              $('#file-preview').empty().hide();
                         },
                         error: function(xhr, status, error) {
                             console.error(error);
                         }
                     });

             });
         });


        function receiver(data){
            var message = data.message.message;

              if (data.message.file != null) {
                 var fileLink = $('<a>').attr('href', data.message.file).attr('target', '_blank').addClass('btn-primary px-2 rounded d-block text-end w-50').text('المرفق').css('margin-right', 'auto');
             }
            var timestamp = data.message.created_at;

            var clearFix = $('<div>').addClass('clearfix');
            var messageContainer = $('<div>').addClass('d-flex align-items-center float-end my-4 mx-4');

            var contentContainer = $('<div>').addClass('align-items-center mx-1');
            var contentText = $('<span>').addClass('d-block text-end').text(message);
            var timestampText = $('<span>').addClass('d-block text-end').text(timestamp);
            var avatarContainer = $('<div>').css({ height: '40px', width: '40px' });
            var avatarImage = $('<img>').addClass('rounded-circle h-100 w-100').attr('src', "{{$adminPhoto}}");


            $('#chat-messages').append(clearFix);
            messageContainer.append(contentContainer);
            contentContainer.append(contentText);
            if (fileLink) {
               contentContainer.append(fileLink);
            }
            contentContainer.append(timestampText);

            messageContainer.append(avatarContainer);


            avatarContainer.append(avatarImage);

            $('#chat-messages').append(messageContainer);

             $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
        }

            $(document).ready(function() {
              $('#message-file').change(function() {
                var file = this.files[0];
                var reader = new FileReader();
                reader.onload = function(e) {
                  $('#file-preview').html('<img  src="' + e.target.result + '" height="50" width="50" style="margin-bottom:5px">');
                }
                reader.readAsDataURL(file);
              });
            });
    </script>