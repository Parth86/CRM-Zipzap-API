<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRole;
use App\Traits\GeneratePassword;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string $original_password
 */
class User extends Authenticatable
{
    use GeneratePassword, HasApiTokens, HasFactory, HasUuid, Notifiable;

    protected $guarded = ['id'];

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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'role' => UserRole::class,
    ];

    /**
     * Scope a query to only include records with a given UUID.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<User>  $query
     * @return \Illuminate\Database\Eloquent\Builder<User>
     */
    public function scopeIsAdmin(Builder $query): Builder
    {
        return $query->where('role', UserRole::ADMIN);
    }

    /**
     * Scope a query to only include records with a given UUID.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<User>  $query
     * @return \Illuminate\Database\Eloquent\Builder<User>
     */
    public function scopeIsemployee(Builder $query): Builder
    {
        return $query->where('role', UserRole::EMPLOYEE);
    }
}
