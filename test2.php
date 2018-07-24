<!DOCTYPE html>
<html>
    <head>
        <title>Chat</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
    </head>
    <body>
        <div id="chat-messages" style="overflow-y: scroll; height: 100px; "></div>        
        <input type="text" class="message">
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>    
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.4/socket.io.js"></script>
    <script>
            var socket = io.connect("ws://127.0.0.1:2021");

            $('.message').on('change', function(){
console.log('send message',$(this).val());
                socket.emit('send message', $(this).val());
                $(this).val('');
            });

            socket.on('new message', function(data){
                $('#chat-messages').append('<p>' + data +'</p>');
            });
    </script>
</html>
