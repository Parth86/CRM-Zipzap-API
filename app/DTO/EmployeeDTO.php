<?php

namespace App\DTO;

use App\Models\User;

class EmployeeDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $phone,
        public readonly ?string $password
    ) {
    }

    public static function fromModel(User $user): self
    {
        return new EmployeeDTO(
            $user->name,
            $user->phone,
            $user->original_password
        );
    }
}
