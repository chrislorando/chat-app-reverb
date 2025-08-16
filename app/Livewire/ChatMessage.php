<?php

namespace App\Livewire;

use App\Events\MessageSent;
use App\Events\PushMessage;
use App\Events\UserOnline;
use App\Events\UserTyping;
use App\Models\User;
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
            'photo' => 'nullable|image|max:10240', // 10MB max
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
        if ($model->file_url) {
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
        ]);
    }
}
