<?php

namespace App\Livewire;

use App\Models\Message;
use Livewire\Attributes\On;
use Livewire\Component;

class ChatUnreadBadge extends Component
{
    public $senderId;
    public $authId;
    public $count = 0;

    public function mount($senderId, $authId)
    {
        $this->senderId = $senderId;
        $this->authId = $authId;

        $this->count = Message::where('sender_id', $this->senderId)
            ->where('receiver_id', $this->authId)
            ->where('read_status', 'Unread')
            ->count();
    }

    public function getListeners()
    {
        return [
            "echo-private:chatlist.{$this->authId},.MessageSent" => 'incrementIfSenderMatches',
        ];
    }

    public function incrementIfSenderMatches($payload)
    {
        if ((int) $this->senderId === (int) $payload['senderId']) {
            $this->count++;
            if($payload['isRead'] === true) {
                $this->count=0;
            }
            
        }
    }

    #[On('refresh-badge')]
    public function refreshBadge()
    {
        $this->dispatch('$refresh');
    }

    public function render()
    {
        return view('livewire.chat-unread-badge');
    }
}
