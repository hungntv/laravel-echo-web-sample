<?php 

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageCreated implements ShouldBroadcast
{
    public $message;
    public $BROADCAST_DRIVER;
    public $channelName;

    public function __construct($message) 
    {
        $this->message = $message;
        $this->BROADCAST_DRIVER = env('BROADCAST_DRIVER', 'null');
        $this->channelName = 'messages_channel';
    }

    public function broadcastOn()
    {
        return new Channel($this->channelName);
    }
}