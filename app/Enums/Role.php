<?php

namespace App\Enums;

use App\Http\Resources\CustomerResource;
use App\Http\Resources\UserResource;
use App\Models\Customer;
use App\Models\User;

enum Role: string
{
    case ADMIN = 'ADMIN';
    case EMPLOYEE = 'EMPLOYEE';
    case CUSTOMER = 'CUSTOMER';

    public function loginGuard(): string
    {
        return match ($this) {
            self::ADMIN => 'web',
            self::EMPLOYEE => 'web',
            self::CUSTOMER => 'customers',
        };
    }

    public function userRole(): ?UserRole
    {
        return match ($this) {
            self::ADMIN => UserRole::ADMIN,
            self::EMPLOYEE => UserRole::EMPLOYEE,
            self::CUSTOMER => null,
        };
    }

    public function toResource(User|Customer $user)
    {
        return match ($this) {
            self::ADMIN => UserResource::make($user),
            self::EMPLOYEE => UserResource::make($user),
            self::CUSTOMER => CustomerResource::make($user),
        };
    }

    public function isCustomer(): bool
    {
        return $this === self::CUSTOMER;
    }

    public function label(): string
    {
        return $this->name;
    }
}
