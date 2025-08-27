<?php

namespace App\Livewire;

use App\Models\Message;
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
    public $thumbnails = [];
    public array $media;
    public $docs;
    public $links;
    public $isProfileOpen = false;
    public $isMediaOpen = false;
    public $isTabMediaOpen = false;
    public $isTabDocsOpen = false;
    public $isTabLinksOpen = false;
    public $isAddContactHeaderOpen = false;

    public function mount($senderId, $authId)
    {
        $this->senderId = $senderId;
        $this->authId = $authId;

        // Initialize the user model with the sender's ID (Incoming messages)
        $this->userModel = User::find($this->senderId);
        $this->thumbnails = Message::whereIn('message_type', ['Image'])
        ->where(function ($q) {
            $q->where('sender_id', auth()->id())
            ->where('receiver_id', $this->senderId)
            ->orWhere('sender_id', $this->senderId)
            ->where('receiver_id', auth()->id());
        })
        ->orderBy('id','desc')
        ->limit(4)
        ->get();

        // dd($this->mediaModel);

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

    #[On('toggle-profile')]
    public function toggleProfile()
    {
        $this->isProfileOpen = !$this->isProfileOpen;
        $this->isTabMediaOpen = false;
        $this->isTabDocsOpen = false;
        $this->isTabLinksOpen = false;

        $this->thumbnails = Message::whereIn('message_type', ['Image'])
            ->where(function ($q) {
                $q->where('sender_id', auth()->id())
                ->where('receiver_id', $this->senderId)
                ->orWhere('sender_id', $this->senderId)
                ->where('receiver_id', auth()->id());
            })
            ->orderBy('id','desc')
            ->limit(4)
            ->get();
    }

    #[On('toggle-media')]
    public function toggleMedia()
    {
        $this->isMediaOpen = !$this->isMediaOpen;
        $this->isTabMediaOpen = false;
        $this->isTabDocsOpen = false;
        $this->isTabLinksOpen = false;
    }

    #[On('toggle-contact-header')]
    public function toggleContactHeader($user = null)
    {
        $this->isAddContactHeaderOpen = !$this->isAddContactHeaderOpen;
        if($user){
            $this->dispatch('set-contact-user', user:$user);
        }
        
    }

    #[On('show-media')]
    public function showMedia()
    {
        $this->isTabMediaOpen = true;
        $this->isTabDocsOpen = false;
        $this->isTabLinksOpen = false;

        $media = Message::whereIn('message_type', ['Image'])
        ->where(function ($q) {
            $q->where(function ($q2) {
                $q2->where('sender_id', auth()->id())
                ->where('receiver_id', $this->senderId);
            })->orWhere(function ($q2) {
                $q2->where('sender_id', $this->senderId)
                ->where('receiver_id', auth()->id());
            });
        })
        ->orderBy('id', 'desc')
        ->get();

        $this->media = $media->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->created_at)->format('F Y'); 
        })
        ->all();
    }

    #[On('show-docs')]
    public function showDocs()
    {
        $this->isTabMediaOpen = false;
        $this->isTabDocsOpen = true;
        $this->isTabLinksOpen = false;

        $this->docs = Message::whereIn('message_type', ['Document'])
        ->where(function ($q) {
            $q->where(function ($q2) {
                $q2->where('sender_id', auth()->id())
                ->where('receiver_id', $this->senderId);
            })->orWhere(function ($q2) {
                $q2->where('sender_id', $this->senderId)
                ->where('receiver_id', auth()->id());
            });
        })
        ->orderBy('id', 'desc')
        ->get();
    }

    #[On('show-links')]
    public function showLinks()
    {
        $this->isTabMediaOpen = false;
        $this->isTabDocsOpen = false;
        $this->isTabLinksOpen = true;

        $this->links = Message::where('content', 'like', '%http%')
        ->where(function ($q) {
            $q->where(function ($q2) {
                $q2->where('sender_id', auth()->id())
                ->where('receiver_id', $this->senderId);
            })->orWhere(function ($q2) {
                $q2->where('sender_id', $this->senderId)
                ->where('receiver_id', auth()->id());
            });
        })
        ->orderBy('id', 'desc')
        ->get();
        // dd($this->links);
    }

    public function render()
    {
        return view('livewire.chat-header');
    }
}
