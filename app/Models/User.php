<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasPushSubscriptions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'about',
        'email',
        'password',
        'avatar'
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
        'avatar_image',
        'avatar_color',
        'avatar_name',
        'alias_name'
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

    public function getAliasNameAttribute()
    {
        return $this->contact?->alias_name;
    }

    public function getInitialsAttribute(): string
    {
        $names = explode(' ', $this->alias_name ?? $this->name);
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

    public function getAvatarNameAttribute(): array
    {
        return [
            'initials' => $this->initials,
            'color' => $this->avatar_color,
            'size' => 'md' // Can make this configurable
        ];
    }

    public function getAvatarImageAttribute()
    {
        return 'https://avatar.iran.liara.run/public/'.$this->id;
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
        return $this->hasOne(Message::class, 'sender_id', 'id')->latestOfMany();
    }

    public function contact()
    {
        return $this->hasOne(Contact::class, 'acquaintance_id', 'id')->where('user_id', auth()->id()); 
    }

    public function contactAlias($acquaintanceId)
    {
        return $this->hasOne(Contact::class, 'user_id', 'id')
            ->where('acquaintance_id', $acquaintanceId);
    }
}
