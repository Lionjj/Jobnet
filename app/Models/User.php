<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use finfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;


class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function savedJobs(){
        return $this->belongsToMany(JobOffert::class, 'saved_jobs')->withTimestamps();
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'skill_user')->withTimestamps();
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'language_user')
                    ->withPivot('level', 'certificate')  // campi extra nella pivot
                    ->withTimestamps();
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

}
