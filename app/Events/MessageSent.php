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

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $receiverId;
    public int $senderId;
    public bool $isRead;


    /**
     * Create a new event instance.
     */
    public function __construct(int $receiverId, int $senderId, bool $isRead = false)
    {
        $this->receiverId = $receiverId;
        $this->senderId = $senderId;
        $this->isRead = $isRead;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("chatlist.{$this->receiverId}"),
        ];
    }

    public function broadcastAs()
    {
        return 'MessageSent';
    }

    public function broadcastWith(): array
    {
        return [
            'senderId' => $this->senderId,
            'receiverId' => $this->receiverId,
            'isRead' => $this->isRead,
        ];
    }
}
