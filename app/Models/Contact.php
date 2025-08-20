<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'alias_name',
        'user_id',
        'acquaintance_id',
        'is_pinned'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function acquaintance()
    {
        return $this->belongsTo(User::class, 'acquaintance_id');
    }
}
