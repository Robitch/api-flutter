<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Character extends Model
{
    protected $fillable = ['name', 'description', 'universe_id', 'creator_id'];

    public function universe()
    {
        return $this->belongsTo(Univers::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
