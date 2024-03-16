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
}
