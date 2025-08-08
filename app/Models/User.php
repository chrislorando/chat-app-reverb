<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $appends = [
        'initials',
        'avatar_color',
        'avatar',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getInitialsAttribute(): string
    {
        $names = explode(' ', $this->name);
        $initials = '';
        
        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
            if (strlen($initials) >= 2) {
                break;
            }
        }
        
        return $initials;
    }

    public function getAvatarColorAttribute(): string
    {
        $colors = [
            'bg-blue-500', 'bg-green-500', 'bg-purple-500', 
            'bg-pink-500', 'bg-red-500', 'bg-yellow-500',
            'bg-indigo-500', 'bg-teal-500'
        ];
        
        $colorIndex = crc32($this->email) % count($colors);
        return $colors[$colorIndex];
    }

    public function getAvatarAttribute(): array
    {
        return [
            'initials' => $this->initials,
            'color' => $this->avatar_color,
            'size' => 'md' // Can make this configurable
        ];
    }

    public function unreadMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id', 'id')->where('read_status', 'Unread');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id', 'id');
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class, 'sender_id', 'id')->latestOfMany(); // atau ->ofMany('created_at', 'max') jika pakai Laravel < 8.42
    }
}
