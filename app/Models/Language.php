<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'name',           // Nome lingua (es. Inglese, Spagnolo)
    ];

    // Relazione con utenti (Many to Many)
    public function users()
    {
        return $this->belongsToMany(User::class, 'language_user')
                    ->withPivot('level', 'certificate')  // campi extra nella pivot
                    ->withTimestamps();
    }
}
