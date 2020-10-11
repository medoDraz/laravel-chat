<?php

namespace App\Events;

use App\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageDelivered1 implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $username;
    public $message;
    public $room_id;
    public $recever_id;
    public $user_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message,$recever_id,$user_id)
    {
        $this->message = $message->body;
        $this->room_id= $message->chat_room_id;
        $this->username = $message->user->name;
        $this->recever_id = $recever_id;
        $this->user_id = $user_id;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {

        return new PrivateChannel('chatgroup1.'.$this->recever_id.'.'.$this->user_id);
    }




}
