<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_id',
        'other_user_id',
        'accept',
        'chat_id'
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
    public function scopeOtherUser($query, $other_user_id)
    {
        if ($other_user_id) {
            return $query->where('other_user_id', $other_user_id);
        }
    }

    public function otherUser() {
        return $this->belongsTo(User::class, 'other_user_id', 'id')->with('role');
    }
    public function chat() {
        return $this->belongsTo(Chat::class, 'chat_id', 'id');
    }
}
