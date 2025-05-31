<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'industry',
        'description',
        'location',
        'mission',
        'vision',
        'company_culture',
        'website',
        'logo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobs()
    {
        return $this->hasMany(JobOffert::class);
    }

    public function benefits()
    {
        return $this->belongsToMany(Benefit::class)->withTimestamps();
    }
}
