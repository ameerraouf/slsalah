@extends('layouts.super-admin-portal')
@section('content')
    <style>
        .messages-container{
            overflow: hidden;
            padding: 10px;
            overflow-y: scroll;
            max-height: 350px;
            min-height: 350px;
        }
        .title{
            text-align: center;
            font-size: 20px;
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
            <div class="col-8 border rounded">
                <div class="hide" style="display: none">

                <div id="chat-messages" class="mt-4 messages-container">
                </div>
                <form id="chat-form" class="mt-1" enctype="multipart/form-data">
                    <input type="text" id="message-input" class="form-control" placeholder="الرسالة">
                    <input type="file" id="message-file" class="form-control my-2" style="display: none;">
                    <label for="message-file" class="custom-file-upload my-2">
                        <i class="fas fa-cloud-upload-alt"></i> ارفاق ملف
                    </label>
                    <div id="file-preview" style="overflow: hidden"></div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary mt-2 my-2">ارسال</button>
                        <button class="btn btn-info mt-2 my-2" id="finish">انهاء المحادثة</button>
                    </div>

                </form>
                </div>
            </div>
            <div class="col-4  p-2">
                @foreach($chats as $chat)
                    <div class="d-flex align-items-center border-top pt-2 cursor-pointer" id="{{$chat->chat_id}}">
                        <div style="height: 40px;width: 40px" class="mx-3">
                            <img class="rounded-circle h-100 w-100" src="{{$chat->sender->photo? url('uploads/' . $chat->sender->photo): url('/'. env('DEFAULT_PHOTO')??"")}}">
                        </div>
                        <div class="align-items-center mx-1">
                            <span class="d-block text-start">{{$chat->sender->first_name . ' '. $chat->sender->last_name}}</span>
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
    sessionStorage.setItem('chat_id',null)
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('{{env('PUSHER_APP_KEY')}}', {
        cluster: 'eu',
        encrypted: true
    });

    var channelReload = pusher.subscribe('reload-page-admin');
          channelReload.bind('reload-page-admin', function(data) {
                location.reload();
         });


    var channel = pusher.subscribe('chat-channel');
    channel.bind('new-message', function(data) {


        if ($('#count'+ data.chat.chat_id).length && sessionStorage.getItem('chat_id') != data.chat.chat_id) {
            var countElement = $('#count'+ data.chat.chat_id);
            var countText = countElement.text().trim();
            var count = parseInt(countText);

            if (!isNaN(count)) { // Check if parsing was successful
                count++;
                countElement.text(count);
            } else {
                countElement.text(1);
            }
        }else{

        }


        //mean the chat is open
        if(sessionStorage.getItem('chat_id') == data.chat.chat_id) {

            var timestamp = data.chat.created_at;
            var message = data.chat.message;

            var clearFix = $('<div>').addClass('clearfix');
            var messageContainer = $('<div>').addClass('d-flex align-items-center float-end my-2');
            console.log(data)
            var contentContainer = $('<div>').addClass('align-items-center mx-1');
            var contentText = $('<span>').addClass('d-block text-end').text(message);
            var timestampText = $('<span>').addClass('d-block text-end').text(timestamp);
            var avatarContainer = $('<div>').css({ height: '40px', width: '40px' });
            var avatarImage = $('<img>').addClass('rounded-circle h-100 w-100').attr('src', data.userPhoto);

            $('#chat-messages').append(clearFix);
            messageContainer.append(contentContainer);
                        contentContainer.append(contentText);
            contentContainer.append(timestampText);
             if (data.chat.file != null) {
                var fileLink = $('<a>').attr('href', data.chat.file).attr('target', '_blank').addClass('btn-primary px-2 rounded d-block w-50 text-end').text('المرفق');
             }

            if (fileLink) {
              contentContainer.append(fileLink);
            }

            messageContainer.append(avatarContainer);
            avatarContainer.append(avatarImage);

            $('#chat-messages').append(messageContainer);

             $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
        }

     });

     $(document).ready(function() {
         $('#chat-form').submit(function(e) {
             e.preventDefault();

             var message = $('#message-input').val();
            var file = $('#message-file')[0].files[0]; // Get the file from the input

            var formData = new FormData(); // Create a FormData object
            if(message){
                formData.append('message', message); // Append the message to the FormData
            }

            formData.append('file', file); // Append the file to the FormData

            formData.append('user_id', sessionStorage.getItem('chat_id'));

             $.ajaxSetup({
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 }
             });


                 $.ajax({
                     url: '/admin/chat',
                     type: 'POST',

                        data: formData, // Use the FormData object as the data
                        processData: false, // Prevent jQuery from converting the data into a query string
                        contentType: false,
                        success: function(response) {

                        var message = response.data.message;
                        var timestamp= response.data.created_at;

                        var clearFix = $('<div>').addClass('clearfix');
                        var messageContainer = $('<div>').addClass('d-flex align-items-center my-2');
                        var avatarContainer = $('<div>').css({ height: '40px', width: '40px' });
                        var avatarImage = $('<img>').addClass('rounded-circle h-100 w-100').attr('src', "{{$adminPhoto}}" );
                        var contentContainer = $('<div>').addClass('align-items-center mx-1');

                        var contentText = $('<span>').addClass('d-block text-start').text(message);
                        var timestampText = $('<span>').addClass('d-block text-start').text(timestamp);


                        if (response.data.file != null) {
                             var fileLink = $('<a>').attr('href', response.data.file).attr('target', '_blank').addClass('btn-primary px-2 rounded').text('المرفق');
                         }


                        $('#chat-messages').append(clearFix);
                        contentContainer.append(contentText);
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
$(document).ready(function() {
    $('#finish').click(function() {

       var chatId = sessionStorage.getItem('chat_id');
       $.ajaxSetup({
         headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
       });

        $.ajax({
            url: '/admin/chat/disable/'+ chatId,
            type: 'POST',
            dataType: 'json',
            data: {
                chatId: chatId
            },
            success: function(response) {


            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
        $(this).parent().parent().hide()
    });
});
    // JavaScript/jQuery code
$(document).ready(function() {
    // Handle chat click event
    $('.cursor-pointer').click(function() {

        var chatId = $(this).attr('id');
        sessionStorage.setItem('chat_id', chatId);
        $('#count'+chatId).text('');
        $('.hide').css('display', 'block');

        $('.messages-container').empty();
        $("#chat-form").show();
        $.ajax({
            url: '/admin/chat/'+ chatId,
            type: 'GET',
            dataType: 'json',
            data: {
                chatId: chatId
            },

            success: function(response) {
                if(response.chatClosed){
                    $("#chat-form").hide();
                }

                response.data.forEach(function(message) {
                    if(message.sender_id == {{auth()->id()}}) {
                      sender(message,response.adminPhoto)
                    }else{
                       receiver(message, response.userPhoto);
                    }
                });

                $('.messages-container').scrollTop($('.messages-container')[0].scrollHeight);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
    function sender(message, adminPhoto) {

            var timestamp= message.created_at;
            if (message.file != null) {
                 var fileLink = $('<a>').attr('href', message.file).attr('target', '_blank').addClass('btn-primary px-2 rounded').text('المرفق');
             }
            var message = message.message;

            var clearFix = $('<div>').addClass('clearfix');
            var messageContainer = $('<div>').addClass('d-flex align-items-center my-2');
            var avatarContainer = $('<div>').css({ height: '40px', width: '40px' });
            var avatarImage = $('<img>').addClass('rounded-circle h-100 w-100').attr('src', adminPhoto );
            var contentContainer = $('<div>').addClass('align-items-center mx-1');
            var contentText = $('<span>').addClass('d-block text-start').text(message);
            var timestampText = $('<span>').addClass('d-block').text(timestamp);

            $('#chat-messages').append(clearFix);
            contentContainer.append(contentText);
            if (fileLink) {
                contentContainer.append(fileLink);
            }
            contentContainer.append(timestampText);



            messageContainer.append(avatarContainer);
            avatarContainer.append(avatarImage);
            messageContainer.append(contentContainer);

            $('#chat-messages').append(messageContainer);


             $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
    }
    function receiver(message, userPhoto) {
            var timestamp = message.created_at;
              if (message.file != null) {
                 var fileLink = $('<a>').attr('href', message.file).attr('target', '_blank').addClass('w-50 mr-auto btn-primary px-2 rounded d-block text-end').text('المرفق').css('margin-right', 'auto');;
             }
            var message = message.message;

            var clearFix = $('<div>').addClass('clearfix');
            var messageContainer = $('<div>').addClass('d-flex align-items-center float-end my-2');

            var contentContainer = $('<div>').addClass('align-items-center mx-1');
            var contentText = $('<span>').addClass('d-block text-end').text(message);
            var timestampText = $('<span>').text(timestamp);
            var avatarContainer = $('<div>').css({ height: '40px', width: '40px' });
            var avatarImage = $('<img>').addClass('rounded-circle h-100 w-100').attr('src', userPhoto);


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
});
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
