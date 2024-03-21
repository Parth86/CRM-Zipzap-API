<?php

namespace App\Models;

use App\Traits\GeneratePassword;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use GeneratePassword, HasFactory, HasUuid, HasApiTokens;

    protected $guarded = ['id'];

    protected $guard = 'customers';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    public function queries(): HasMany
    {
        return $this->hasMany(Query::class);
    }
}
