<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class NewMessages implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id;
    public $from_name;
    public $from_email;
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id, $from_name, $from_email, $message)
    {
        $this->id = $id;
        $this->from_name = $from_name;
        $this->from_email = $from_email;
        $this->message = $message;

        // $chatUser = DB::table('chat_users')->where('chat_id', $id)->where('user_id','!=', $from)->orderByDesc('id')->first();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('messages.'.$this->to);
        return new Channel('livechat');
    }

    public function broadcastAs()
    {
      return 'new_messages';
    }
}
