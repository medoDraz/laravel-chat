<?php

use App\Events\MessageDelivered;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('online1', function ($user) {
   
    return $user;
    
});
Broadcast::channel('online.{roomId}', function ($user, $roomId) {
    $chatRoom = App\ChatRoom::find($roomId);
    if(in_array($user->id, explode(',', $chatRoom->user_id))) {
        return $user;
    } else {
        return false;
    }
});

Broadcast::channel('chatgroup.{roomId}', function ($user, $roomId) {

    $chatRoom = App\ChatRoom::find($roomId);
    if(in_array($user->id, explode(',', $chatRoom->user_id))) {
        return $user;
    } else {
        return false;
    }

});

Broadcast::channel('chatgroup1.{user_id}.{userId}', function ($user, $user_id, $userId) {
	
	$chatRoom=App\ChatRoom::where('room_type','private')
			->where('user_id', 'like', '%' . $userId . '%')
			->where('user_id', 'like', '%' . $user_id . '%')
			->first();

    //$chatRoom = App\ChatRoom::find($room1->id);
    if(in_array($user->id, explode(',', $chatRoom->user_id))) {
        return $user;
    } else {
        return false;
    }

});

Broadcast::channel('online-video.{roomId}', function ($user, $roomId) {
    $chatRoom = App\ChatRoom::find($roomId);
    if(in_array($user->id, explode(',', $chatRoom->user_id))) {
        return $user;
    } else {
        return false;
    }
});

//Broadcast::channel('chatgroup', function ($user, $roomId) {
//
//    $chatRoom = App\ChatRoom::find($roomId);
//    if(in_array($user->id, explode(',', $chatRoom->user_id))) {
//        return $user;
//    } else {
//        return false;
//    }
//
//});




