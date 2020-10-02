<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\ChatRoom;
use App\Message;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
		$user=\Auth::user();
		//dd($user);
		$users=User::where('id','!=',$user->id)->get();
		$chatrooms=ChatRoom::where('room_type','group')
			->where('user_id', 'like', '%' . $user->id . '%')
			->get();
        return view('home',compact('users','chatrooms'));
    }
	
	public function getmessages(Request $request)
    {
		$user=\Auth::user();
		if($request->room_id == null){
			$room1=ChatRoom::where('room_type',$request->type)
			->where('user_id', 'like', '%' . $user->id . '%')
			->where('user_id', 'like', '%' . $request->recever_id . '%')
			->first();
			if($room1 == null){
				
				$roomMembers = [$user->id, $request->recever_id];
				sort($roomMembers);
				$roomMembers = implode(',', $roomMembers);
				
				$recever=User::find($request->recever_id)->first();
				
				$room1 = new ChatRoom();
				$room1->name = $user->name.'-'.$request->recever_id;
				$room1->room_type = $request->type;
				$room1->user_id = $roomMembers;
				$room1->save();
			}
			$messages = Message::with('user')->where('chat_room_id',$room1->id)->get();
			return response()->json([
				'messages' => $messages
			]);
		}else{
			$messages = Message::with('user')->where('chat_room_id',$request->room_id)->get();
			return response()->json([
				'messages' => $messages
			]);
		}
		
    }
}
