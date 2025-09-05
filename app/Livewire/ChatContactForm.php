<?php

namespace App\Livewire;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class ChatContactForm extends Component
{
    public $user_id;
    public $acquaintance_id;
    public $full_name;
    public $email;
    public bool $isNewRecord = true;
    public bool $isExists = false;

    // protected $rules = [
    //     'full_name' => 'required|string|max:255',
    //     'email' => [
    //         'required',
    //         'email',
    //         'max:255',
    //         Rule::unique('users', 'email')->ignore(auth()->id()),
    //     ]
    // ];

    public function mount(bool $isNewRecord = true, User $user)
    {
        $this->isNewRecord = $isNewRecord;
        if($isNewRecord){
            $this->acquaintance_id = null;
            $this->full_name = null;
            $this->email = null;
        }

        if(!$isNewRecord){
            $this->acquaintance_id = $user->id;
            $this->full_name = $user->alias_name ?? $user->name;
            $this->email = $user->email;
        }
    }

    #[On('set-contact-user')]
    public function setContactUser($isNewRecord=true, User $user)
    {
        if($isNewRecord){
            $this->acquaintance_id = null;
            $this->full_name = null;
            $this->email = null;
        }

        if(!$isNewRecord){
            $this->acquaintance_id = $user->id;
            $this->full_name = $user->alias_name ?? $user->name;
            $this->email = $user->email;
        }
        
    }

    public function updatedEmail()
    {
        $this->resetErrorBag();
        $user = User::where('email', $this->email)->first();
        if($user){
            $isExists = Contact::where('user_id', auth()->id())
            ->where('acquaintance_id', $user->id)
            ->exists();

            $this->isExists = $isExists && $this->isNewRecord ? true : false;
            $this->acquaintance_id = $user->id;
        }else{
            $this->isExists = false;
        }
        
    }

    public function save()
    {
        $this->validate([
            'full_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('contacts')
                ->where(fn ($q) => $q->where('user_id', auth()->id()))
                ->ignore($this->acquaintance_id),
            ],
        ]);

        $user = User::where('email', $this->email)->first();

        if (! $user) {
            $this->addError('email', 'User with this email does not exist.');
            return;
        }

        $contact = Contact::withTrashed()
            ->firstOrNew([
                'user_id' => auth()->id(),
                'acquaintance_id' => $user->id,
            ]);

        $contact->alias_name = $this->full_name;

        $contact->exists && $contact->trashed()
            ? $contact->restore()
            : null;

        $contact->save();

        session()->flash('success', 'Contact saved.');

    
        if($this->isNewRecord){
            $this->resetExcept('acquaintance_id');
            // $this->dispatch('chat', uid:$contact->acquaintance_id)->to(ChatList::class);
        }
    }

    public function dispatchToChat()
    {
        $this->dispatch('chat', uid:$this->acquaintance_id)->to(ChatList::class);
    }

    public function destroy($contactId)
    {
        Contact::where('user_id', auth()->id())->where('acquaintance_id', $contactId)->delete();
        $this->dispatch('open-home')->to(ChatMessage::class);
        $this->dispatch('refresh-list')->to(ChatList::class);
    }

    public function render()
    {
        return view('livewire.chat-contact-form');
    }
}
