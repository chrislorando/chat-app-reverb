<?php

namespace App\Livewire;

use App\Events\MessageSent;
use App\Events\PushMessage;
use App\Events\UserOnline;
use App\Events\UserTyping;
use App\Models\Contact;
use App\Models\User;
use App\Notifications\PushMessageBrowser;
use App\Services\AI\AIServiceInterface;
use Illuminate\Support\Facades\Broadcast;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;
use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Models\Message;
use Livewire\WithFileUploads;
use Storage;

class ChatMessage extends Component
{
    use WithFileUploads;

    private $models;
    private $newModels;
    private $oldModels;
    public User $userModel;
    public $uid;
    public $userId1;
    public $userId2;
    public string $message = '';
    public $showChat = false;
    public $perPage = 10;
    public $key;
    public $targetSender;
    public $targetMessageId;
    public $targetMessageText;
    public $targetMessageType;
    public $targetMessageFileUrl;
    public $targetMessageFileSize;
    public $targetMessageFileName;
    public $isOnline;
    public bool $isTyping = false;
    
    #[Validate('image|max:1024')]
    public $photo;
    public $document;
    public $cameraImage;
    public $showCamera = false;
    public $cameraStream;
    public $contactList = [];
    public $searchContact = '';
    public $selectedContacts = [];
    public $isOpenWsDrawer = false;
    public $wsCategories = ['Rephrase','Professional','Funny','Supportive','Proofread'];
    public $selectedWsCategory;
    public $generatedOptions = [];

    public function mount()
    {
        $this->userModel = new User();   
        $this->dispatch('$refresh');

    }

    // #[Renderless]
    // #[On('loadMore')] 
    public function loadMore()
    {
        $this->perPage += 5;
    }

   
    public function handleFileUpload($type, $file)
    {
        if ($type === 'document') {
            $this->document = $file;
        } elseif ($type === 'photo') {
            $this->photos[] = $file; // Untuk multiple photos
        } elseif ($type === 'camera') {
            $this->cameraImage = $file;
            $this->showCamera = false; // Tutup kamera setelah capture
        }
    }

    public function openCamera()
    {
        $this->showCamera = true;
        // $this->dispatchBrowserEvent('open-camera');
    }

    public function closeCamera()
    {
        $this->showCamera = false;
        // $this->dispatchBrowserEvent('close-camera');
    }

    public function addEmoji($emoji): void
    {
        $this->message .= $emoji;
    }


    public function send()
    {
        $this->validate([
            'message' => 'nullable|string',
            'photo' => 'nullable|image|max:1024', // 10MB max
            'document' => 'nullable|file|max:10240|mimes:pdf,doc,docx,txt,xls,xlsx,ppt', // 10MB max
        ], [
            'message.required_without_all' => 'Either a message or a file is required',
        ]);
        
        if (trim($this->message) === '' && !$this->photo && !$this->document) {
            return;
        }

        // $this->dispatch('$refresh');

        $data = [
            'sender_id'=> auth()->id(),
            'receiver_id'=> $this->uid,
            'parent_id'=> $this->targetMessageId,
            'content' => $this->message,
            'interaction_type' => $this->targetMessageId ? 'Reply' : null,
            'timestamp' => now()
        ];

        if ($this->document) {
            $path = $this->document->storePublicly('documents');
            $data = array_merge($data, [
                'message_type' => 'Document',
                'file_name' => $this->document->getClientOriginalName(),
                'file_size' => $this->document->getSize(),
                'file_url' => Storage::url($path),
                // 'content' => 'Document: ' . $this->document->getClientOriginalName()
            ]);
        }

        if (!empty($this->photo)) {
            $path = $this->photo->storePublicly('images');
            $data = array_merge($data, [
                'message_type' => 'Image',
                'file_name' => $this->photo->getClientOriginalName(),
                'file_size' => $this->photo->getSize(),
                'file_url' => Storage::url($path),
                // 'content' => 'Photo: ' . $this->photo->getClientOriginalName()
            ]);
        }

        if ($this->cameraImage) {
            $filename = 'camera_' . time() . '.jpg';
            $path = $this->cameraImage->storeAs('photos', $filename);
            $data = array_merge($data, [
                'message_type' => 'Image',
                'file_name' => $filename,
                'file_size' => $this->cameraImage->getSize(),
                'file_url' => Storage::url($path),
                // 'content' => 'Camera photo'
            ]);
        }
        
        // dd($data);
        Message::create($data);

        $this->userId1 = min(auth()->id(), $this->uid);
        $this->userId2 = max(auth()->id(), $this->uid);

        event(new PushMessage($this->userId1, $this->userId2, $this->message));
        event(new MessageSent($this->uid, auth()->id()));
        $this->dispatch('refresh-list')->to(ChatList::class);

        $receiver = User::find($this->uid);
        $aliasOfMe = $receiver->contactAlias(auth()->id())->first()?->alias_name ?? auth()->user()->name;
        $receiver->notify(new PushMessageBrowser(auth()->id(), auth()->user()->avatar_image, $aliasOfMe, $this->message));

        // $this->closeUploadDrawer();
        $this->reset([
            'message',
            'targetMessageId',
            'targetMessageText',
            'targetMessageType',
            'targetMessageFileUrl',
            'targetMessageFileSize',
            'targetMessageFileName',
            'targetSender',
            'isTyping',
            'photo', 
            'document', 
            'cameraImage'
        ]);

        // $this->dispatch('$refresh');
    }

    public function sendPublic()
    {
           // Broadcast::on('private-room.'.auth()->id())->sendNow();
        //    event(new PushMessage(auth()->id(), 'Private message'));
           // broadcast(new PushMessage(auth()->id(), $this->message));
           // $this->dispatch('open-chat', uid:$this->uid)->to(ChatMessage::class);
           // dd($this->message);
    }

    public function sendPrivate()
    {
        // Broadcast::on('private-room.'.auth()->id())->sendNow();
        // event(new PushMessage(auth()->id(), 'Private message'));
        // broadcast(new PushMessage(auth()->id(), $this->message));
        // $this->dispatch('open-chat', uid:$this->uid)->to(ChatMessage::class);
        // dd($this->message);
    }

    // public function getListeners()
    // {
    //     // Check if both user IDs are available
    //     if ($this->userId1 !== null && $this->userId2 !== null) {
    //         // dd("echo-private:room.{$this->userId1}.{$this->userId2},PushMessage");
    //         // Return the listener for the private channel with the appropriate channel name
    //         return [
    //             // "echo-private:room.{$this->userId1}.{$this->userId2},PushMessage" => 'pushMessage',
    //              "echo-private:room.{$this->userId1}.{$this->userId2},.PushMessage" => 'pushMessage',
    //         ];
    //     }else{
    //          // If user IDs are not available, return an empty array
    //         // return [
    //         //     "echo-private:room,PushMessage" => 'pushMessage',
    //         // ];

    //         return [];
    //     }
       

    //     // return [
    //     //     "echo-private:room.{$this->userId1}.{$this->userId2},.PushMessage" => 'pushMessage',
    //     // ];
    // }

    #[On('open-chat')] 
    public function refreshChat($uid, $userId1, $userId2, $action=null)
    {
        $this->uid = $uid;
        $this->userId1 = $userId1;
        $this->userId2 = $userId2;
        $this->showChat = true;
        $this->message = "";
        $this->dispatch('$refresh');
        $this->isOnline = true;

        Message::where('receiver_id', auth()->id())
        ->where('sender_id', $this->uid)
        ->update([
            'read_status'=> 'Read',
            'updated_at' => now()
        ]);

        event(new MessageSent(auth()->id(), $this->uid, true));
        $this->closeUploadDrawer();
        $this->closeWsDrawer();
        // event(new UserOnline(auth()->id(),  true));

        // $this->dispatch('read-chat')->to(ChatList::class);
        // dd($this->userId1, $this->userId2, $this->uid);
        // event(new PushMessage($this->userId1, $this->userId2, 'ENTER'));

    }

    #[On('open-home')] 
    public function openHome()
    {
        $this->showChat = false;
        $this->dispatch('$refresh');
    }

    #[On('echo:room,PushMessage')] 
    public function testAllChannel($data)
    {
        // dd("NOT PRIVATE");
        // $this->dispatch('open-chat', uid:$this->uid)->to(ChatMessage::class);
    }

    // #[On('echo-private:room.{uid},PushMessage')] 
    #[On('triggerMessage')] 
    public function pushMessage()
    {
        // dd($event);
        // dd("echo-private:room.".$this->userId1.".".$this->userId2);
        // $this->dispatch('open-chat', uid:$this->uid)->to(ChatMessage::class);

        $this->dispatch('$refresh');
        event(new MessageSent(auth()->id(), $this->uid, true));
    }

    public function reply(Message $model)
    {
        $this->targetSender = $model->sender->name;
        $this->targetMessageId = $model->id;
        $this->targetMessageText = $model->content;
        $this->targetMessageType = $model->message_type;
        $this->targetMessageFileName = $model->file_name;
        $this->targetMessageFileSize = $model->file_size;
        $this->targetMessageFileUrl = $model->fileUrl();
    }


    public function clearReply()
    {
        $this->targetSender = null;
        $this->targetMessageId = null;
        $this->targetMessageText = null;
        $this->targetMessageType = null;
        $this->targetMessageFileName = null;
        $this->targetMessageFileSize = null;
        $this->targetMessageFileUrl = null;
    }

    public function closeUploadDrawer()
    {
        $this->message = '';
        $this->photo = null;
        $this->document = null;
        $this->cameraImage = null;
    }


    public function remove(Message $model)
    {
        // Hapus file jika ada file_url
        if ($model->file_url && $model->interaction_type == null) {
            // Ambil path relatif dari file_url
            $parsedUrl = parse_url($model->file_url, PHP_URL_PATH);
            // Misal: /chat-reverb/images/krgffGRjmyiCDWmvj7USjC76lsL9DFcN8iSs2vzR.png
            // Hilangkan leading slash dan nama bucket
            $path = ltrim($parsedUrl, '/');
            $bucket = config('filesystems.disks.minio.bucket', 'chat-reverb');
            if (str_starts_with($path, $bucket . '/')) {
                $path = substr($path, strlen($bucket) + 1);
            }
            // Hapus file dari storage
            // Determine the correct disk, defaulting to 's3' if not set
            $disk = config('filesystems.default', 's3');
            Storage::disk($disk)->delete($path);
        }

        $model->delete();
      
        $this->userId1 = min(auth()->id(), $this->uid);
        $this->userId2 = max(auth()->id(), $this->uid);

        event(new PushMessage($this->userId1, $this->userId2, $this->message));
    }

    public function typing()
    {
        if (! $this->isTyping) {
            event(new UserTyping(auth()->id(), $this->uid, true));
            $this->isTyping = true;
        }
    }

    public function notTyping()
    {
        event(new UserTyping(auth()->id(), $this->uid,  false));
        $this->isTyping = false;
    }

    #[On('get-contact-list')]
    public function getContactList($targetMessageId=null)
    {
        // $this->targetMessageId = $targetMessageId ?? null;
        // $this->targetMessageText = $targetMessageText ?? null;
        $this->reply(Message::find($targetMessageId));

        $this->contactList = Contact::when($this->searchContact, function ($query) {
                $query->where(function ($q) {
                    $q->where('alias_name', 'like', '%' . $this->searchContact . '%')
                      ->orWhereHas('acquaintance', function ($q) {
                          $q->where('name', 'like', '%' . $this->searchContact . '%')
                            ->orWhere('email', 'like', '%' . $this->searchContact . '%');
                      });
                });
            })
            ->where('user_id', auth()->id())
            ->with('acquaintance')
            ->orderBy('alias_name', 'asc')
            ->get()
            ->map(function ($contact) {
                return [
                    'id' => $contact->id,
                    'acquaintance_id' => $contact->acquaintance?->id,
                    'name' => $contact->alias_name ?: $contact->acquaintance->name,
                    'about' => $contact->acquaintance?->about,
                    'avatar_initials' => $contact->acquaintance?->avatar['initials'],
                    'avatar_color' => $contact->acquaintance?->avatar['color'],
                ];

            })->toArray();

    }

    public function updatedSearchContact()
    {
        $this->getContactList();
        // dd($this->targetMessageId, $this->targetMessageText);
    }
    
    public function sendForwardMessage()
    {
         $this->validate([
            'selectedContacts' => 'required',
            'targetMessageId' => 'required',
        ]);

        $data = [];

        // dd($this->targetMessageId, $this->targetMessageFileUrl);
        foreach($this->selectedContacts as $row){
            [$receiverId] = explode('|', $row);

            $data[] = [
                'sender_id' => auth()->id(),
                'receiver_id' => $receiverId,
                'parent_id' => $this->targetMessageId,
                'content' => $this->targetMessageText,
                'message_type' => $this->targetMessageType,
                'file_name' => $this->targetMessageFileName,
                'file_size' => $this->targetMessageFileSize,
                'file_url' => $this->targetMessageFileUrl,
                'interaction_type' => 'Forward',
                'timestamp' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $this->userId1 = min(auth()->id(), $receiverId);
            $this->userId2 = max(auth()->id(), $receiverId);

            event(new PushMessage($this->userId1, $this->userId2, $this->targetMessageText));
            event(new MessageSent($receiverId, auth()->id()));
            $this->dispatch('refresh-list')->to(ChatList::class);

            $receiver = User::find($receiverId);
            $aliasOfMe = $receiver->contactAlias(auth()->id())->first()?->alias_name ?? auth()->user()->name;
            $receiver->notify(new PushMessageBrowser(auth()->id(), auth()->user()->avatar_image, $aliasOfMe, $this->targetMessageText));
        }

        Message::insert($data);

        $this->reset([
            'message',
            'targetMessageId',
            'targetMessageText',
            'targetMessageType',
            'targetMessageFileUrl',
            'targetMessageFileSize',
            'targetMessageFileName',
            'targetSender',
            'isTyping',
            'photo', 
            'document', 
            'cameraImage'
        ]);

        $this->dispatch('save-forwarded-message');

        // dd($this->selectedContacts, $this->targetMessageId, $this->targetMessageText);
    }

    public function updatedMessage()
    {
        $this->isOpenWsDrawer = true;
    }

    public function setWsCategory($value)
    {
        $this->selectedWsCategory = $value;    
        $this->generateText(app(AIServiceInterface::class));
    }

    public function generateText(AIServiceInterface $service)
    {
        if($this->message == ''){
            return;
        }

        if($this->selectedWsCategory == ''){
            $this->selectedWsCategory = $this->wsCategories[0];
        }

        $prompt = "Kamu adalah text transformer yang HANYA mengeluarkan hasil transformasi tanpa penjelasan, sapaan, atau penutup apapun. Ubah kalimat '{$this->message}' menjadi 3 versi {$this->selectedWsCategory} dengan kalimat panjang maksimal 150 karakter; gunakan bahasa natural sehari-hari. Keluaran: 3 baris.";
        $rs = $service->generateText($prompt);

        $lines = preg_split('/\r\n|\r|\n/', trim($rs['text']));

        $items = [];
        foreach ($lines as $line) {
            $line = trim($line, "* \t\n\r\0\x0B");
            if ($line !== '') {
                $clean = preg_replace('/[[:punct:]&&[^,.?!]]/', '', $line);
                $items[] = $clean;
            }
        }

        $this->generatedOptions = $items;
    }

    public function closeWsDrawer()
    {
        $this->isOpenWsDrawer = false;
        $this->message = '';
        $this->selectedWsCategory = null;
    }

    public function render()
    {
        $this->models = [];
        $this->models = Message::where('sender_id', auth()->id())
        ->where('receiver_id', $this->uid)
        ->orWhere('sender_id', $this->uid)
        ->where('receiver_id', auth()->id())
        // ->take(5)
        ->orderBy('id','desc')
        // ->latest()
        ->paginate($this->perPage);

        if($this->uid)
        {
            $this->userModel = User::find($this->uid);
        }
      
        

        #TODO: Should be moved to middleware or give some logic
        // event(new UserOnline(auth()->id(),  true));
        return view('livewire.chat-message', [
            'models' => $this->models,
            'uid' => $this->uid,
        ]);
    }
}
