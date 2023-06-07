<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $table = 'conversations';

    protected $fillable = [
        'character_id',
        'user_id',
    ];

    public function characters()
    {
        return $this->belongsTo(Character::class, 'character_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
