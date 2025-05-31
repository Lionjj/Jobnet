<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
{
    protected $fillable = ['name'];

    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }

    public function jobOfferts()
    {
        return $this->belongsToMany(JobOffert::class, 'job_offert_benefit')->withTimestamps();
    }

}

