<?php

namespace App\Enums;

enum UserRole: int
{
    case ADMIN = 1;
    case EMPLOYEE = 2;

    public function label(): string
    {
        return $this->name;
    }

    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    public function isEmployee(): bool
    {
        return $this === self::EMPLOYEE;
    }
}
