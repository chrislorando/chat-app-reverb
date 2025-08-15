<?php

namespace App\Livewire;

use App\Livewire\Actions\Logout;
use App\Models\Contact;
use App\Models\Message;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\User;

class ChatList extends Component
{
    private $models;

    public $search;

    public $isActiveChat;

    public $perPage = 10;

    public $uid;
    public $isAddContactOpen = false;
    

    public function loadMore()
    {
        $this->perPage += 10;
    }


    public function chat($uid)
    {
        $this->isActiveChat = null;
        $this->uid = $uid;

        $userId1 = min(auth()->id(), $this->uid);
        $userId2 = max(auth()->id(), $this->uid);


        $this->dispatch('open-chat', uid:$uid, userId1:$userId1, userId2:$userId2, action:'init')->to(ChatMessage::class);
        $this->dispatch('toggle-profile')->to(ChatHeader::class);
        $this->dispatch('toggle-media')->to(ChatHeader::class);
        $this->isActiveChat = $uid;
    }

    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }

    #[On('toggle-contact')]
    public function toggleContact()
    {
        $this->isAddContactOpen = !$this->isAddContactOpen;
    }

    #[On('refresh-list')]
    public function refreshList()
    {
        $this->dispatch('$refresh');
    }

    // #[On('read-chat')] 
    public function render()
    {
        $authId = auth()->id();

        $contacts = Contact::where('user_id', $authId)->pluck('acquaintance_id');
        $existChatUser = Message::where(function ($q) use ($authId) {
                $q->where('sender_id', $authId)
                ->orWhere('receiver_id', $authId);
            })
            ->get()
            ->flatMap(function ($message) use ($authId) {
                return [
                    $message->sender_id == $authId ? $message->receiver_id : $message->sender_id
                ];
            })
            ->unique()
            ->values();


        $mergedContact = $contacts->merge($existChatUser)->unique()->values();

        $this->models = User::whereIn('id', $mergedContact)
        ->when($this->search != '', function ($query) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('username', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        })
        ->where('id', '<>', $authId)
        ->withCount(['sentMessages as unread_messages_count' => function (Builder $query) use ($authId) {
            $query->where('receiver_id', $authId)
                  ->where('read_status', 'Unread');
        }])
        // ->with(['latestMessage'])
        ->limit(10)
        ->paginate($this->perPage);  


        $this->models->setCollection(
            $this->models->getCollection()->map(function ($user) use ($authId) {
                $user->latest_message = Message::where(function ($q) use ($authId, $user) {
                        $q->where('sender_id', $authId)
                        ->where('receiver_id', $user->id);
                    })
                    ->orWhere(function ($q) use ($authId, $user) {
                        $q->where('receiver_id', $authId)
                        ->where('sender_id', $user->id);
                    })
                    ->latest()
                    ->first();
                return $user;
            })
        );
        return view('livewire.chat-list', [
            'models' => $this->models
        ]);
    }
}
