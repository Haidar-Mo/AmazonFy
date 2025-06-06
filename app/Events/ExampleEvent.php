<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExampleEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('example-channel');
    }

    // Optional: Customize broadcast name
    public function broadcastAs()
    {
        return 'example.event';
    }

    // Optional: Add data to broadcast
    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'time' => now()->toDateTimeString()
        ];
    }
}
