<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobOffert extends Model
{
    protected $fillable = [
        'company_id',
        'title',
        'description',
        'location',
        'job_type',           // ex: smart working, ibrido, ecc.
        'contract_type',      // ex: tempo indeterminato, stage, ecc.
        'experience_level',   // ex: Junior, Middle, Senior
        'ral',                // retribuzione annua lorda
        'skills_required',    // JSON con elenco competenze
        'benefits',           // JSON con benefit aziendali
        'is_active',          // per attivare/disattivare l’annuncio
    ];

    protected $casts = [
        'skills_required' => 'array',
        'benefits' => 'array',
        'ral' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'saved_jobs')->withTimestamps();
    }

}
