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
    </style>
    <div class="container mt-5">

        <h1>Chat App</h1>
        <div class="row">
            <div class="col-8">
                <div id="chat-messages" class="mt-4 messages-container">

                </div>
                <form id="chat-form" class="mt-1">
                    <input type="text" id="message-input" class="form-control" placeholder="Type your message">
                    <button type="submit" class="btn btn-primary mt-2">Send</button>
                </form>
            </div>
            <div class="col-4">
                @foreach($chats as $chat)
                    <div class="d-flex align-items-center cursor-pointer " id="{{$chat->sender_id}}">
                        <div style="height: 40px;width: 40px">
                            <img class="rounded-circle h-100" src="{{url('uploads/' . $chat->sender->photo)}}">
                        </div>
                        <div class="align-items-center mx-1">
                            <span class="d-block text-start">{{$chat->sender->first_name . ' '. $chat->sender->last_name}}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


    </div>

@endsection

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('{{env('PUSHER_APP_KEY')}}', {
        cluster: 'eu',
        encrypted: true
    });

    var channel = pusher.subscribe('chat-channel');
    channel.bind('new-message', function(data) {
        // Append new message to the chat messages
{{--        $('#chat-messages').append('<p>' + data.chat.message + '</p>');--}}

    var message = data.chat.message;
        var timestamp = data.chat.created_at;
$('#chat-messages').append('<hr>');

        var messageContainer = $('<div>').addClass('d-flex align-items-center');
        var avatarContainer = $('<div>').css({ height: '40px', width: '40px' });
        var avatarImage = $('<img>').addClass('rounded-circle h-100').attr('src', 'http://adsoldiers.test/assets_en/imgs/home/banner-logo.png');
        var contentContainer = $('<div>').addClass('align-items-center mx-1');
        var contentText = $('<span>').addClass('d-block text-start').text(message);
        var timestampText = $('<span>').text(timestamp);

        contentContainer.append(contentText);
        contentContainer.append(timestampText);

        messageContainer.append(avatarContainer);
        avatarContainer.append(avatarImage);
        messageContainer.append(contentContainer);

        $('#chat-messages').append(messageContainer);

         $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
     });

     $(document).ready(function() {
         $('#chat-form').submit(function(e) {
             e.preventDefault();

             var message = $('#message-input').val();
             $.ajaxSetup({
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 }
             });

             if (message !== '') {
                 // Send the message to the backend
                 $.ajax({
                     url: '/admin/chat',
                     type: 'POST',

                     data: {
                         message: message
                     },
                     success: function(response) {
                         // Clear the input field
                         $('#message-input').val('');
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

        // Clear existing messages
        $('.message-container').empty();

        // Load messages for the selected chat
        $.ajax({
            url: '/admin/chat/'+ chatId,
            type: 'GET',
            dataType: 'json',
            data: {
                chatId: chatId
            },
            success: function(response) {
                response.data.forEach(function(message) {


                    if(message.sender_id == 1) {
                    console.log('fdslkfjds')
                        sender(message,response.userPhoto)
                    }else{
                       receiver(message,response.adminPhoto);
                    }
                });

                // Scroll to the bottom of the messages container
                $('.messages-container').scrollTop($('.messages-container')[0].scrollHeight);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
    function sender(message) {
    console.log('am admin')
            var message = message.message;
            var timestamp= message.created_at;

            var clearFix = $('<div>').addClass('clearfix');
            var messageContainer = $('<div>').addClass('d-flex align-items-center my-2');
            var avatarContainer = $('<div>').css({ height: '40px', width: '40px' });
            var avatarImage = $('<img>').addClass('rounded-circle h-100').attr('src', adminPhoto );
            var contentContainer = $('<div>').addClass('align-items-center mx-1');
            var contentText = $('<span>').addClass('d-block text-start').text(message);
            var timestampText = $('<span>').text(timestamp);

            $('#chat-messages').append(clearFix);
            contentContainer.append(contentText);
            contentContainer.append(timestampText);

            messageContainer.append(avatarContainer);
            avatarContainer.append(avatarImage);
            messageContainer.append(contentContainer);

            $('#chat-messages').append(messageContainer);


             $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
    }
    function receiver(message, userPhoto) {
            var timestamp = message.created_at;
            var message = message.message;

            var clearFix = $('<div>').addClass('clearfix');
            var messageContainer = $('<div>').addClass('d-flex align-items-center float-end my-2');

            var contentContainer = $('<div>').addClass('align-items-center mx-1');
            var contentText = $('<span>').addClass('d-block text-end').text(message);
            var timestampText = $('<span>').text(timestamp);
            var avatarContainer = $('<div>').css({ height: '40px', width: '40px' });
            var avatarImage = $('<img>').addClass('rounded-circle h-100').attr('src', userPhoto);

            $('#chat-messages').append(clearFix);
            messageContainer.append(contentContainer);
                        contentContainer.append(contentText);
            contentContainer.append(timestampText);

            messageContainer.append(avatarContainer);
            avatarContainer.append(avatarImage);

            $('#chat-messages').append(messageContainer);

             $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
    }
});
</script>
