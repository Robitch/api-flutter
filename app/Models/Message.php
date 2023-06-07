<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'content',
        'is_sent_by_human',
        'conversation_id',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
