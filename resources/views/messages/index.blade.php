@extends('layouts.app')

@section('content')

    <div class="container" xmlns="">
        <div class="row justify-content-center">
            <div class="card" style="width: 80em;">
                <div class="card-header">
                    Room Chat {{ $room->name}}
                    <a href="{{ route('add.user',$room->id) }}" class="btn btn-sm btn-primary float-right"><i
                            class="fa fa-plus"> add user</i></a>
                    <a href="{{ route('room.video.chat',$room->id) }}" class="btn btn-sm  float-right" style="background-color: white">
                        <i class="fa fa-video-camera" style="color: red; font-size: 20px;"></i></a>
                </div>

                <div class="card-body" style="display: inline-flex;">
                    <div class="col-md-3 col-sm-3" style="border-radius: 10px; background: #f7f7f7; padding-top: 8px;">

                        <h4>Online Users</h4>
                        <hr>

                        <h5 id="no-online">No Users Online</h5>

                        <ul class="list-group" id="online-users">

                        </ul>

                    </div>

                    <div class="col-md-9 col-sm-9 d-flex flex-column" style="height: 68vh">
                        <div class="h-100 bg-white mb-4 p-5" id="chat" style="overflow-y: scroll">
                            @foreach($messages as $message)
                                <div id="message-div"
                                     class="mt-4  text-white p-2 rounded {{ auth()->user()->id == $message->user_id ?'float-right bg-primary' : 'float-left bg-warning'}}">
                                    <p style="{{ auth()->user()->id == $message->user_id ?'text-align: right ' : 'text-align: left '}}">{{ $message->user->name }}</p>
                                    <p style="{{ auth()->user()->id == $message->user_id ?'text-align: right ' : 'text-align: left '}}">{{ $message->body }}</p>
                                </div>
                                <div class="clearfix"></div>
                            @endforeach
                        </div>

                        <form action="" class="d-flex">
                            <input type="text" name="body" data-url="{{ route('messages.store') }}"
                                   style="margin-right: 10px;"
                                   class="form-control" id="chat-text"/>

                            <input type="hidden" id="room_id" name="room_id" value="{{ $room->id}}"/>
                            <button class="btn btn-primary" data-url="{{ route('messages.store') }}" id="send">Send
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">

        let chatWindow = document.getElementById('chat');
        var xH = chatWindow.scrollHeight;
        chatWindow.scrollTo(0, xH);
        let onlineuserslength = 0;
        let roomId = $('#room_id').val();
        console.log(roomId);
        window.Echo.join(`online.${roomId}`)
            .here((users) => {
                onlineuserslength = users.length;
                if (users.length > 1) {
                    $('#no-online').css('display', 'none');
                }
                let userId = $('meta[name=user-id]').attr('content');
                users.forEach(function (user) {
                    if (user.id == userId) {
                        return;
                    }
                    $('#online-users').append(`<li id="user-${user.id}" class="list-group-item"><span class="fa fa-circle text-success"></span>  ${user.name}</li>`);
                });
                // console.log(users);
            })
            .joining((user) => {
                onlineuserslength++;
                $('#no-online').css('display', 'none');
                $('#online-users').append(`<li id="user-${user.id}" class="list-group-item"><span class="fa fa-circle text-success"></span>  ${user.name}</li>`);
            })
            .leaving((user) => {
                onlineuserslength--;
                if (onlineuserslength == 1) {
                    $('#no-online').css('display', 'block');
                }
                $('#user-' + user.id).remove();
            });

        $('#send').on('click', function (e) {
            e.preventDefault();
            let body = $('#chat-text').val();
            let room_id = roomId;
            let url = $(this).data('url');
            let username = $('meta[name=user-name]').attr('content');

            let data = {
                '_token': $('meta[name=csrf-token]').attr('content'),
                body,
                'chat_room_id': room_id
            };

            $.ajax({
                url: url,
                method: 'post',
                data: data,
                success: function (data) {
                    $('#chat-text').val('');
                    $('#chat').append(`
                            <div id="message-div" class="mt-4  text-white p-2 rounded float-right bg-primary">
                                <p style="text-align: right">${username}</p>
                                <p style="text-align: right">${data.message.body}</p>
                            </div>
                            <div class="clearfix"></div>`
                    );
                    let chatWindow = document.getElementById('chat');
                    var xH = chatWindow.scrollHeight;
                    chatWindow.scrollTo(0, xH);
                }
            });
        });

        $('#chat-text').keypress(function (e) {
            if (e.which == 13) {
                e.preventDefault();
                let body = $(this).val();
                let room_id = $('#room_id').val();
                let url = $(this).data('url');
                let username = $('meta[name=user-name]').attr('content');

                let data = {
                    '_token': $('meta[name=csrf-token]').attr('content'),
                    body,
                    'chat_room_id': room_id
                };
                $(this).val('');
                $.ajax({
                    url: url,
                    method: 'post',
                    data: data,
                    success: function (data) {
                        $(this).val('');
                        $('#chat').append(`
                            <div id="message-div" class="mt-4  text-white p-2 rounded float-right bg-primary">
                                <p style="text-align: right">${username}</p>
                                <p style="text-align: right">${data.message.body}</p>
                            </div>
                            <div class="clearfix"></div>`
                        );
                        let chatWindow = document.getElementById('chat');
                        var xH = chatWindow.scrollHeight;
                        chatWindow.scrollTo(0, xH);
                    }
                });
            }
        });
         window.Echo.private(`chatgroup.${roomId}`)
            .whisper('typing', {
                name: 'this.user',
            });

        window.Echo.private(`chatgroup.${roomId}`)
            .listen('MessageDelivered', e => {
                console.log(e);
                $('#chat').append(`
                            <div id="message-div" class="mt-4  text-white p-2 rounded float-left bg-warning">
                                <p style="text-align: left">${e.username}</p>
                                <p style="text-align: left">${e.message}</p>
                            </div>
                            <div class="clearfix"></div>`
                );
                let chatWindow = document.getElementById('chat');
                var xH = chatWindow.scrollHeight;
                chatWindow.scrollTo(0, xH);
            }).listenForWhisper("typing", (e) => {
                
                console.log('e');
            });
          
    </script>
@endsection

