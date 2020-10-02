@extends('layouts.app')

@section('content')

    <div class="container" xmlns="">
        <div class="row justify-content-center">
            <div class="card" style="width: 80em;">
                <div class="card-header">
                    Video Chat Room {{ $room->name}}
                </div>
                <div class="card-body" style="display: inline-flex;">
                    <div class="col-md-3 col-sm-3" style="border-radius: 10px; background: #f7f7f7; padding-top: 8px;">
                        <video playsinline autoplay id="myVideo" style="border: 1px solid;width: 100%;"></video>
                        <h4>Online Users</h4>
                        <hr>

                        <h5 id="no-online">No Users Online</h5>

                        <ul class="list-group" id="online-users">

                        </ul>

                    </div>
                    <div class="col-md-9 col-sm-9 d-flex flex-column" style="height: 78vh;">
                        <input type="hidden" id="room_id" name="room_id" value="{{ $room->id}}"/>
                        <h2>User Video</h2>
                        <div class=" px-4" id="others">
                            <video class="uservideo px-2" playsinline autoplay id="uservideo" style="border: 1px solid; width: 100%; height: 100%;"></video>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
       
        let user_stream = null;
        var my_video = document.querySelector("video#myVideo");
        var user_video = document.querySelector("video#uservideo");
        
        let onlineuserslength = 0;
        let roomId = $('#room_id').val();
        
        console.log(roomId);

        window.Echo.join(`online-video.${roomId}`)
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
                // $('#others').append(`<video class="uservideo px-2" playsinline autoplay id="uservideo-${user.id}" style="border: 1px solid; width: 100%; height: 100%;"></video>`);

                // var user_video = document.querySelector("video.uservideo");
            });
            // console.log(users);
        })
        .joining((user) => {
            onlineuserslength++;
            $('#no-online').css('display', 'none');
            $('#online-users').append(`<li id="user-${user.id}" class="list-group-item"><span class="fa fa-circle text-success"></span>  ${user.name}</li>`);
            // $('#others').append(`<video class="uservideo px-2" playsinline autoplay id="uservideo-${user.id}" style="border: 1px solid; width: 100%; height: 100%;"></video>`);
            // var user_video = document.querySelector("video.uservideo");
        })
        .leaving((user) => {
            onlineuserslength--;
            if (onlineuserslength == 1) {
                $('#no-online').css('display', 'block');
            }
            $('#user-' + user.id).remove();
            // $('#uservideo-' + user.id).remove();
        });
            

//         //////////////////###Connect#####//////////////////////////
//         var conn = peer.connect('another-peers-id');
//         conn.on('open', function(id){
//             // here you have conn.id
//             console.log('My peer ID is: ' + id);
//             conn.send('hi!');
//         });
//         //////////////////###Receive#####//////////////////////////
//         peer.on('connection', function(conn) {
//             conn.on('data', function(data){
//                 // Will print 'hi!'
//                 console.log(data);
//             });
//         });
//         //////////////////###Media calls Call#####//////////////////////////
//         var getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
//         getUserMedia({video: true, audio: true}, function(stream) {
//             var call = peer.call('another-peers-id', stream);
//             call.on('stream', function(remoteStream) {
//                 // Show stream in some video/canvas element.
//             });
//         }, function(err) {
//             alert('Failed to get local stream' ,err);
//         });
//         //////////////////###Media calls Answer#####//////////////////////////
//         peer.on('call', function(call) {
//         getUserMedia({video: true, audio: true}, function(stream) {
//             call.answer(stream); // Answer the call with an A/V stream.
//             call.on('stream', function(remoteStream) {
//             // Show stream in some video/canvas element.
//             });
//         }, function(err) {
//             alert('Failed to get local stream' ,err);
//         });
//         });
// var peer = new Peer({
//                 initiator: true,
//                 trickle: false,
//                 // stream: stream
//             });
//             console.log(peer);
var Peer = require('simple-peer');
        navigator.mediaDevices.getUserMedia({video: true, audio: true})
        .then((stream) => {
            var Peer = require('simple-peer');
            var peer = new Peer({
                initiator: true,
                trickle: false,
                stream: stream
            });
            peer.on('open', function(id) {
                console.log('My peer ID is: ' + id);
            });
            var call = peer.call('dest-peer-id', stream);
            call.answer(stream);
            call.on('stream', function(stream) {
                console.log(stream);
                try {
                    user_video.srcObject = stream;
                } catch (e) {
                    user_video.src = URL.createObjectURL(stream);
                }

                user_video.play();
            });
            
            // user_stream=stream;
            try {
                my_video.srcObject = stream;
            } catch (e) {
                my_video.src = URL.createObjectURL(stream);
            }
            my_video.play();
        })
        .catch(err => {
            alert(`Unable to fetch devices! please check your devices (camera and microphone) are required `);
        //    console.log(`Unable to fetch devices! please check your devices (camera and microphone) are required `);
        });
        
    </script>
@endsection