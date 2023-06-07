<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Univers extends Model
{
    protected $table = 'univers'; // Nom de la table dans la base de données

    protected $fillable = ['name', 'creator_id']; // Champs pouvant être remplis massivement

    // Relation avec le modèle User pour le créateur de l'univers
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function characters()
    {
        return $this->hasMany(Character::class, 'universe_id');
    }
}
