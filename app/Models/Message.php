<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'parent_id',
        'sender_id',
        'receiver_id',
        'content',
        'timestamp',
        'delivery_status',
        'read_status',
        'message_type',
        'interaction_type',
        'file_name',
        'file_size',
        'file_url',
        'created_at',
        'updated_at',
    ];

    public function fileUrl()
    {
        return $this->file_url ? asset($this->file_url) : null;
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
  
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function parent()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }
}
