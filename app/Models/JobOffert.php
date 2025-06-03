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
        'is_active',          // per attivare/disattivare l’annuncio
    ];

    protected $casts = [
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

    public function benefits()
    {
        return $this->belongsToMany(Benefit::class, 'job_offert_benefit')->withTimestamps();
    }


    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'job_offert_skill')->withTimestamps();
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

}
