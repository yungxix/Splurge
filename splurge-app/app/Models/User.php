<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles() {
        return $this->hasMany(UserRole::class);
    }

    public function scopeOfRole($builder, $name) {
        return $builder->whereHas('roles', function ($q) use ($name) {
            return $q->where('name', 'like', $name);
        });
    }

    public function hasRole($name) { 
        return $this->roles->contains(function ($role, $k) use ($name){
            return strnatcasecmp($role->name, $name) === 0;
        });
    }

    public function hasAnyRole(array $names) {
        return $this->roles->contains(function ($role, $k) use ($names) {
            return in_array($role->name, $names);
        });
    }
}
