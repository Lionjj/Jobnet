<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = ['name'];
    public $timestamps = false;

    public static function booted()
    {
        static::creating(function ($skill) {
            $skill->name = strtolower($skill->name);
        });

        static::updating(function ($skill) {
            $skill->name = strtolower($skill->name);
        });
    }
    public function jobOfferts()
    {
        return $this->belongsToMany(JobOffert::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'skill_user')->withTimestamps();
    }
}
