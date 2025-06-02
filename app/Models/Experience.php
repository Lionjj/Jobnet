<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = [
        'user_id',        // ID utente proprietario esperienza
        'company',   // Nome azienda
        'role',           // Ruolo/posizione lavorativa
        'start_date',     // Data inizio
        'end_date',       // Data fine (nullable se attuale)
        'description',    // Descrizione mansioni/responsabilità
        'is_current',     // Se è ancora in corso (boolean)
    ];
    
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    // Relazione con utente (Many to One)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
