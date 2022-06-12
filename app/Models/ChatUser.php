<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatUser extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'chat_id',
        'user_id',
        'pin',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function scopeUser($query, $user_id)
    {
        if ($user_id) {
            return $query->where('user_id', $user_id);
        }
    }
    public function scopeChat($query, $chat_id)
    {
        if ($chat_id) {
            return $query->where('chat_id', $chat_id);
        }
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function chat() {
        return $this->belongsTo(Chat::class, 'chat_id', 'id');
    }
}
