<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',        // ID utente proprietario del profilo
        'type',       // Piattaforma (es. GitHub, LinkedIn, Twitter)
        'url',            // URL del profilo
    ];

    // Relazione con utente (Many to One)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
