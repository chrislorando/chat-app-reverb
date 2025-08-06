<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserTyping implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $senderId;
    public int $receiverId;
    public bool $isTyping;

    /**
     * Create a new event instance.
     */
    public function __construct(int $senderId, int $receiverId, bool $isTyping = false)
    {
        $this->senderId = $senderId;
        $this->receiverId = $receiverId;
        $this->isTyping = $isTyping;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("chat.{$this->receiverId}"),
        ];
    }

    public function broadcastAs()
    {
        return 'UserTyping';
    }

    public function broadcastWith(): array
    {
        return [
            'senderId' => $this->senderId,
            'receiverId' => $this->receiverId,
            'isTyping' => $this->isTyping,
        ];
    }
}
