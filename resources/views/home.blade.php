@extends('layouts.app')

@section('content')
<div class="container bg-white p-2" style="display: inline-flex; max-width:100%;">
   
				@if (session('status'))
					<div class="alert alert-success" role="alert">
						{{ session('status') }}
					</div>
				@endif

				<div class="col-md-4 col-sm-4" style="border-right: 1px solid #f0f0f0; padding-right: 0;">
				
                        <div class="msg-inbox msg-vbox" style="height:70%">
                            <div class="msg-title">
                                <h3>Inbox</h3>
                            </div>
                            
                            <!-- ngIf: !loadInbox -->
                            <ul class="inbox-list ng-scope" id="online-users">
							
								@foreach($users as $user)
									<li class="ng-scope">
										<a href="" class="user-massage" data-userid="{{$user->id}}" data-type="private" data-room_id="" data-url="{{ route('messages.getmessages') }}">
											<div class="list-message-thumb" style="display: inline-flex;">
											 <span class="fa fa-circle" id="online-{{$user->id}}" style="color: #e3342f;"></span>
												<img alt="" width="45" class="ng-isolate-scope" src="{{ $user->image_path }}">
											</div>
											<div class="list-message-content">
												<h5 class="title ng-binding">{{$user->name}} </h5>
											</div>
											<div class="list-config"></div>
										</a> 
									</li>
								@endforeach
                              
                            </ul><!-- end online Users -->
                            <div class="txt-center mt-lg">
                                <!-- ngIf: loadInbox -->
                            </div>
                        </div>
						
						<div class="msg-inbox msg-vbox" style="height:30%">
                            <div class="msg-title">
                                <h3>Chat Groups</h3>
                            </div>
                            
                            <!-- ngIf: !loadInbox -->
                            <ul class="inbox-list ng-scope" id="online-users">
							
								@foreach($chatrooms as $chatroom)
									<li class="ng-scope">
										<a href="" data-room_id="{{$chatroom->id}}" data-userid="" data-type="group" data-url="{{ route('messages.index',$chatroom->id) }}" class="user-massage">
											<div class="list-message-thumb" style="display: inline-flex;">
												<img alt="" width="45" class="ng-isolate-scope" src="{{ $user->image_path }}">
											</div>
											<div class="list-message-content">
												<h5 class="title ng-binding">{{$chatroom->name}} </h5>
											</div>
											<div class="list-config"></div>
										</a> 
									</li>
								@endforeach
                              
                            </ul><!-- end online Users -->
                            <div class="txt-center mt-lg">
                                <!-- ngIf: loadInbox -->
                            </div>
                        </div>
                    </div>
				<div class="col-md-8 col-sm-8 d-flex flex-column" style="height: 92vh;">

                        <div class="msg-container msg-vbox ng-scope">
                            <div class="msg-position">
                                <div class="msg-body ng-isolate-scope " id="message-content"
                                     style="overflow-y: scroll; height: 91%;">
                                    <!-- ngIf: !loadedMsg -->
                                    <div class="ng-scope1" >
                                      
                                        
                                    </div><!-- end ngIf: !loadedMsg -->

                                    <!-- ngIf: loadedMsg -->
                                </div>


                                <div class="msg-sender" >
                                    <div class="typing-area">
                                        <div class="text-holder">
											<input style="width: 86%;"
												class="form-control ng-pristine ng-untouched ng-valid ng-empty"
												placeholder="type your message here..."
												data-url="{{ route('messages.store') }}"
												id="message">
											</input>
											<input type="hidden" id="recever_id" name="recever_id" value=""/>
											<input type="hidden" id="room_id" name="room_id" value=""/>
											<input type="hidden" id="type" name="type" value=""/>
											<button class="btn btn-sm btn-primary" data-url="{{ route('messages.store') }}" id="send">Send
											</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
			
</div>
@endsection

@section('script')
    <script type="text/javascript">

        let chatWindow = document.getElementById('message-content');
        var xH = chatWindow.scrollHeight;
        chatWindow.scrollTo(0, xH);
        let onlineuserslength = 0;
        let roomId = $('#room_id').val();
        //console.log(roomId);
        window.Echo.join(`online1`)
            .here((users) => {
                onlineuserslength = users.length;
                if (users.length > 1) {
                    $('.msg-title1').css('display', 'none');
                }
                let userId = $('meta[name=user-id]').attr('content');
                users.forEach(function (user) {
                    if (user.id == userId) {
                        return;
                    }
                    $('#online-'+ user.id).css('color' , 'green');
                });
                // console.log(users);
            })
            .joining((user) => {
                onlineuserslength++;
                $('#online-'+ user.id).css('color' , 'green');
            })
            .leaving((user) => {
                onlineuserslength--;
                if (onlineuserslength == 1) {
                    $('.msg-title1').css('display', 'block');
                }
                $('#online-'+ user.id).css('color' , '#e3342f');
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
                    $('#chat').append('<div id="message-div" class="mt-4  text-white p-2 rounded float-right bg-primary"><p style="text-align: right">'+'+name+'+'</p><p style="text-align: right">'+data.message.body+'</p></div><div class="clearfix"></div>');
                    let chatWindow = document.getElementById('message-content');
                    var xH = chatWindow.scrollHeight;
                    chatWindow.scrollTo(0, xH);
                }
            });
        });
		
		$('.user-massage').on('click', function (e) {
            e.preventDefault();
			$('.ng-scope1').empty();
            let url = $(this).data('url');
            let type = $(this).data('type');
            let room_id = $(this).data('room_id');
			let userId = $('meta[name=user-id]').attr('content');
			let user_id = $(this).data('userid');
			$('#room_id').val(room_id);
			$('#type').val(type);
			$('#recever_id').val(user_id);
			console.log(user_id);
            let data = {
                '_token': $('meta[name=csrf-token]').attr('content'),
				'recever_id' : user_id,
				'type' : type,
				'room_id' :room_id
            };
			

            $.ajax({
                url: url,
                method: 'post',
                data: data,
                success: function (data) {
                    console.log(data.messages.length);
					
					data.messages.forEach(function(entry) {
						var t1=new Date(entry.created_at);
						var t2=new Date();
						
						var total = (t1 - t2) ;
						var totalD =  Math.abs(Math.floor(total/1000));
						var years = Math.floor(totalD / (365*60*60*24));
						var months = Math.floor((totalD - years*365*60*60*24) / (30*60*60*24));
						var days = Math.floor((totalD - years*365*60*60*24 - months*30*60*60*24)/ (60*60*24));
						var hours = Math.floor((totalD - years*365*60*60*24 - months*30*60*60*24 - days*60*60*24)/ (60*60));
						var minutes = Math.floor((totalD - years*365*60*60*24 - months*30*60*60*24 - days*60*60*24 - hours*60*60)/ (60));
						var seconds = Math.floor(totalD - years*365*60*60*24 - months*30*60*60*24 - days*60*60*24 - hours*60*60 - minutes*60);

						var Y = years < 1 ? "" : years + " Years ";
						var M = months < 1 ? "" : months + " Months ";
						var D = days < 1 ? "" : days + " Days ";
						var H = hours < 1 ? "" : hours + " Hours ";
						var I = minutes < 1 ? "" : minutes + " Minutes ";
						var S = seconds < 1 ? "" : seconds + " Seconds ";
						var A = years == 0 && months == 0 && days == 0 && hours == 0 && minutes == 0 && seconds == 0 ? "Sending" : " ago";

						if(entry.user_id == userId){
							$('.ng-scope1').append('<div class="message ng-scope right"><div class="message-container"><div class="message-setting"><span class="date ng-binding">you</span></div><div class="message-text ng-binding">'+entry.body+'</div><div class="message-setting"><span class="date ng-binding">'+Y + M + D + H + I + S + A+'</span></div><img class="_uavatar ng-isolate-scope" width="32" src="{{ asset('img/') }}'+'/'+entry.user.image+'"></div></div><!-- end ngRepeat: m in messages track by m.id -->');
						} else {
							$('.ng-scope1').append('<div class="message ng-scope left"><div class="message-container"><div class="message-setting"><span class="date ng-binding">'+entry.user.name+'</span></div><div class="message-text ng-binding">'+entry.body+'</div><div class="message-setting"><span class="date ng-binding">'+Y + M + D + H + I + S + A+'</span></div><img class="_uavatar ng-isolate-scope" width="32" src="{{ asset('img/') }}'+'/'+entry.user.image+'"></div></div><!-- end ngRepeat: m in messages track by m.id -->');
						}
					});
				
                    let chatWindow = document.getElementById('message-content');
                    var xH = chatWindow.scrollHeight;
                    chatWindow.scrollTo(0, xH);
                }
            });
        });
		
		

        $('#message').keypress(function (e) {
            if (e.which == 13) {
                e.preventDefault();
                let body = $(this).val();
                let room_id = $('#room_id').val();
                let recever_id = $('#recever_id').val();
                let type = $('#type').val();
                let url = $(this).data('url');
                let username = $('meta[name=user-name]').attr('content');
				
                let data = {
                    '_token': $('meta[name=csrf-token]').attr('content'),
                    body,
                    'chat_room_id': room_id,
                    'recever_id': recever_id,
                    'type': type,
                };
                $(this).val('');
				
				
				
				$.ajax({
                    url: url,
                    method: 'post',
                    data: data,
                    success: function (data) {
                        $(this).val('');
						console.log(data);
                        $('.ng-scope1').append(
							'<div class="message ng-scope right"><div class="message-container"><div class="message-setting"><span class="date ng-binding">you</span></div><div class="message-text ng-binding">'+data.message.body+'</div><div class="message-setting"><span class="date ng-binding">1 s</span></div><img class="_uavatar ng-isolate-scope" width="32" src="{{ asset('img/') }}'+'/'+data.message.user.image+'"></div></div><!-- end ngRepeat: m in messages track by m.id -->'
                        );
                        let chatWindow = document.getElementById('message-content');
                        var xH = chatWindow.scrollHeight;
                        chatWindow.scrollTo(0, xH);
                    }
                });
                
            }
        });
		
			window.Echo.private(`chatgroup`)
            .listen('MessageDelivered', e => {
                console.log(e);
                $('.ng-scope1').append(
							'<div class="message ng-scope left"><div class="message-container"><div class="message-setting"><span class="date ng-binding">'+e.username+'</span></div><div class="message-text ng-binding">'+e.message+'</div><div class="message-setting"><span class="date ng-binding">1 s</span></div><img class="_uavatar ng-isolate-scope" width="32" src="{{ asset('img/user2.jpg') }}"></div></div><!-- end ngRepeat: m in messages track by m.id -->'
                        );
                let chatWindow = document.getElementById('message-content');
                var xH = chatWindow.scrollHeight;
                chatWindow.scrollTo(0, xH);
            });
        
          
    </script>
@endsection
