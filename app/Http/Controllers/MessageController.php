<?php

namespace App\Http\Controllers;

use App\ChatRoom;
use App\Events\MessageDelivered;
use App\Message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Broadcasting\InteractsWithSockets;
class MessageController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index($room_id)
    {
        $room = ChatRoom::where('id', $room_id)->first();

        $messages = Message::with('user')->where('chat_room_id', $room_id)->get();

        return response()->json([
            'messages' => $messages
        ]);
    }

    public function store(Request $request)
    {
		$user=\Auth::user();
		if($request->room_id == null){
			$room1=ChatRoom::where('room_type',$request->type)
			->where('user_id', 'like', '%' . $user->id . '%')
			->where('user_id', 'like', '%' . $request->recever_id . '%')
			->first();
			$message = $user->messages()->create([
				'body' => $request->body,
				'chat_room_id' => $room1->id,
			]);
			broadcast(new MessageDelivered($message))->toOthers();
			return response()->json([
				'success'=>'get your data',
				'message' => $message
			]);
		}
        $message = $user->messages()->create($request->all());
        broadcast(new MessageDelivered($message))->toOthers();
        return response()->json([
            'success'=>'get your data',
            'message' => $message
        ]);
    }

    public function getrooms()
    {
        $user_id = auth()->user()->id;
        $rooms = ChatRoom::where('user_id', 'like', '%' . $user_id . '%')->get();
        return view('messages.rooms', compact('rooms'));
    }

    public function addroomform()
    {
        return view('messages.add_chat_room');
    }

    public function adduserroomform($room_id)
    {
        $user_id = auth()->user()->id;
        $users = User::where('id', '!=', $user_id)->get();
        $room = ChatRoom::where('id', $room_id)->first();
        return view('messages.add_user_inroom', compact('users', 'room'));
    }

    public function adduserroom(Request $request)
    {
        $room = ChatRoom::find($request->room_id);
        $roomMembers = [$room->user_id, $request->users_id];
        sort($roomMembers);
        $roomMembers = implode(',', $roomMembers);

        $room->user_id = $roomMembers;
        $room->save();

        $messages = Message::where('chat_room_id', $request->room_id)->get();
        return view('messages.index', compact('messages', 'room'));
    }

    public function addroom(Request $request)
    {

        $room1 = new ChatRoom();
        $room1->name = $request->name;
        $room1->room_type = 'group';
        $room1->user_id = $request->user_id;
        $room1->save();

        $user_id = auth()->user()->id;
        $rooms = ChatRoom::where('user_id', 'like', '%' . $user_id . '%')->get();
        return view('messages.rooms', compact('rooms'));
    }

    public function videochat(Request $request)
    {
        $room = ChatRoom::where('id', $request->room_id)->first();
        return view('messages.videochat',compact('room'));
    }
}