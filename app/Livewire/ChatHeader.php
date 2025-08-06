<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class ChatHeader extends Component
{
    public $authId;
    public $senderId;
    public $isTyping = false;
    public $isOnline = false;
    public User $userModel;

    public function mount($senderId, $authId)
    {
        $this->senderId = $senderId;
        $this->authId = $authId;

        // Initialize the user model with the sender's ID (Incoming messages)
        $this->userModel = User::find($this->senderId);

    }

    public function getListeners()
    {
        // Listen for typing events (incoming messsages) in receiver's channel
        return [
            "echo-private:chat.{$this->authId},.UserTyping" => 'showTyping',
            // "echo-private:presence-status,.UserOnline" => 'showOnline',
            // "presence-online" => 'showOnline',
        ];
    }

    public function showTyping($payload)
    {
        // dd($payload['senderId'], $this->senderId);
        // Check if the senderId in the payload matches the component's senderId
        if ($payload['senderId'] === $this->senderId) {
            $this->isTyping = $payload['isTyping'];
        }
    }

    // public function showOnline($payload)
    // {
    //     // dd($payload['senderId'], $this->senderId);
    //     // Check if the senderId in the payload matches the component's senderId
    //     if ($payload['userId'] === $this->senderId) {
    //         $this->isOnline = $payload['isOnline'];
    //     }
    // }


    #[On('presence-online')]
    public function showOnline($userId=null, $isOnline=false, $forceBroadcast=false, $respondTo=null)
    {
        // Tangani broadcast status
        if ($userId === $this->senderId) {
            $this->isOnline = $isOnline;

            // Jika ada permintaan broadcast balik
            if (isset($forceBroadcast)) {
                $this->broadcastMyStatus();
            }
            
            // Jika ada permintaan khusus dari user lain
            if (isset($respondTo) && $respondTo === $this->authId) {
                $this->broadcastMyStatus();
            }
        }
        
        
    }
    
    public function broadcastMyStatus()
    {
        $this->dispatch('presence-online', [
            'userId' => $this->authId,
            'isOnline' => true,
            'forceBroadcast' => true
        ]);
    }

    public function render()
    {
        return view('livewire.chat-header');
    }
}
