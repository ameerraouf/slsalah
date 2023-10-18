@extends('layouts.primary')
@section('content')
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
            <div id="chat-messages" class="mt-4 messages-container">
                @foreach($messages as $message)
                    @if($message->sender_id == auth()->id())
                        <div class="d-flex align-items-center my-2">
                            <div style="height: 40px;width: 40px">
                                <img class="rounded-circle h-100 w-100" src="{{$userPhoto}}">
                            </div>
                            <div class="align-items-center mx-1">
                                <span class="d-block text-start">{{$message->message}}</span>
                                <span>{{$message->created_at}}</span>
                            </div>
                        </div>
                    @else
                        <div class="d-flex align-items-center float-end ">
                            <div class="align-items-center mx-1">
                                <span class="d-block text-end">{{$message->message}}</span>
                                <span>{{$message->created_at}}</span>
                            </div>
                            <div style="height: 40px;width: 40px">
                                <img class="rounded-circle h-100 w-100" src="{{$adminPhoto}}">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    @endif
                @endforeach


            </div>
            <form id="chat-form" class="mt-1">
                <input type="text" id="message-input" class="form-control" placeholder="Type your message">
                <button type="submit" class="btn btn-primary mt-2">Send</button>
            </form>

        </div>

    @endsection

    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        Pusher.logToConsole = true;

        var pusher = new Pusher('{{env('PUSHER_APP_KEY')}}', {
        cluster: 'eu',
        encrypted: true
    });


    var channel = pusher.subscribe('chat-channel');
    channel.bind('new-message', function(data) {

            if(data.chat.receiver_id == {{auth()->id()}}) {
                receiver(data)
            }
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
                         url: '/user/chat',
                         type: 'POST',

                         data: {
                             message: message
                         },
                         success: function(response) {
                             // Clear the input field
                             var data = response.data;

                             var message = data.message;
                             var timestamp = data.created_at;

                            var clearFix = $('<div>').addClass('clearfix');
                            var messageContainer = $('<div>').addClass('d-flex align-items-center my-2');
                            var avatarContainer = $('<div>').css({ height: '40px', width: '40px' });
                            var avatarImage = $('<img>').addClass('rounded-circle h-100').attr('src',"{{$userPhoto}}" );
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
                             $('#message-input').val('');
                         },
                         error: function(xhr, status, error) {
                             console.error(error);
                         }
                     });
                 }
             });
         });

        function sender(data)
        {

        }

        function receiver(data){
            var message = data.chat.message;
            var timestamp = data.chat.created_at;

            var clearFix = $('<div>').addClass('clearfix');
            var messageContainer = $('<div>').addClass('d-flex align-items-center float-end my-2');

            var contentContainer = $('<div>').addClass('align-items-center mx-1');
            var contentText = $('<span>').addClass('d-block text-end').text(message);
            var timestampText = $('<span>').text(timestamp);
            var avatarContainer = $('<div>').css({ height: '40px', width: '40px' });
            var avatarImage = $('<img>').addClass('rounded-circle h-100').attr('src', 'http://adsoldiers.test/assets_en/imgs/home/banner-logo.png');

            $('#chat-messages').append(clearFix);
            messageContainer.append(contentContainer);
                        contentContainer.append(contentText);
            contentContainer.append(timestampText);

            messageContainer.append(avatarContainer);
            avatarContainer.append(avatarImage);

            $('#chat-messages').append(messageContainer);

             $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
        }
    </script>