<?php

namespace App\Models;

use App\Traits\GeneratePassword;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string $original_password
 */
class Customer extends Authenticatable
{
    use GeneratePassword, HasFactory, HasUuid, HasApiTokens;

    protected $guarded = ['id'];

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
}
