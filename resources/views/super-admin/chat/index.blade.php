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
    </style>
    <div class="container mt-5">

        <h3>جلسات دعم ومساعدة</h3>
        <div class="row">
            <div class="col-8 border rounded">
                <div class="hide" style="display: none">

                <div id="chat-messages" class="mt-4 messages-container">
                </div>
                <form id="chat-form" class="mt-1" enctype="multipart/form-data">
                    <input type="text" id="message-input" class="form-control" placeholder="Type your message">
                    <input type="file" id="message-file" class="form-control my-2" >
                    <button type="submit" class="btn btn-primary mt-2">Send</button>
                </form>
                </div>
            </div>
            <div class="col-4  p-2">
                @foreach($chats as $chat)
                    <div class="d-flex align-items-center border-top pt-2 cursor-pointer" id="{{$chat->sender_id}}">
                        <div style="height: 40px;width: 40px">
                            <img class="rounded-circle h-100" src="{{url('uploads/' . $chat->sender->photo)}}">
                        </div>
                        <div class="align-items-center mx-1">
                            <span class="d-block text-start">{{$chat->sender->first_name . ' '. $chat->sender->last_name}}</span>
                        </div>
                        <div class="align-items-center ">
                            <span class="text-danger mx-3" id="count{{$chat->sender_id}}"></span>
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

    var channel = pusher.subscribe('chat-channel');
    channel.bind('new-message', function(data) {
        //if this sender id not found in the list create a new and click on it
        // if chat exist and not open
        console.log('session '+ sessionStorage.getItem('chat_id'));

        if ($('#count'+ data.chat.sender_id).length && sessionStorage.getItem('chat_id') != data.chat.sender_id) {
            var countElement = $('#count'+ data.chat.sender_id);
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
        if(sessionStorage.getItem('chat_id') == data.chat.sender_id) {
        console.log('new mesage ');
            var timestamp = data.chat.created_at;
            var message = data.chat.message;

            var clearFix = $('<div>').addClass('clearfix');
            var messageContainer = $('<div>').addClass('d-flex align-items-center float-end my-2');

            var contentContainer = $('<div>').addClass('align-items-center mx-1');
            var contentText = $('<span>').addClass('d-block text-end').text(message);
            var timestampText = $('<span>').addClass('d-block text-end').text(timestamp);
            var avatarContainer = $('<div>').css({ height: '40px', width: '40px' });
            var avatarImage = $('<img>').addClass('rounded-circle h-100').attr('src', "{{$userPhoto}}");

            $('#chat-messages').append(clearFix);
            messageContainer.append(contentContainer);
                        contentContainer.append(contentText);
            contentContainer.append(timestampText);
             if (data.chat.file != null) {
                var fileLink = $('<a>').attr('href', data.chat.file).attr('target', '_blank').addClass('text-danger d-block text-end').text('show file');
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
            formData.append('message', message); // Append the message to the FormData
            formData.append('file', file); // Append the file to the FormData

            formData.append('user_id', sessionStorage.getItem('chat_id'));

             $.ajaxSetup({
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 }
             });

             if (message !== '') {
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
                             var fileLink = $('<a>').attr('href', response.data.file).attr('target', '_blank').addClass('text-danger').text('show file');
                         }


                        $('#chat-messages').append(clearFix);
                        contentContainer.append(contentText);
                        contentContainer.append(timestampText);

                        if (fileLink) {
                          contentContainer.append(fileLink);
                        }
                        messageContainer.append(avatarContainer);
                        avatarContainer.append(avatarImage);
                        messageContainer.append(contentContainer);
                        $('#chat-messages').append(messageContainer);


                         $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
                         $('#message-input').val('');
                         $('#message-file').val('');
                     },
                     error: function(xhr, status, error) {
                         console.error(error);
                     }
                 });
             }
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

        $('.message-container').empty();
        $.ajax({
            url: '/admin/chat/'+ chatId,
            type: 'GET',
            dataType: 'json',
            data: {
                chatId: chatId
            },
            success: function(response) {
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
                 var fileLink = $('<a>').attr('href', message.file).attr('target', '_blank').addClass('text-danger').text('show file');
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
            contentContainer.append(timestampText);

            if (fileLink) {
                contentContainer.append(fileLink);
            }

            messageContainer.append(avatarContainer);
            avatarContainer.append(avatarImage);
            messageContainer.append(contentContainer);

            $('#chat-messages').append(messageContainer);


             $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
    }
    function receiver(message, userPhoto) {
            var timestamp = message.created_at;
              if (message.file != null) {
                 var fileLink = $('<a>').attr('href', message.file).attr('target', '_blank').addClass('text-danger d-block text-end').text('show file');
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
</script>
