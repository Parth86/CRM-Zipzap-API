<?php

namespace App\DTO;

use App\Models\Customer;
use App\Models\User;

class UserDTO
{
    public function __construct(
        public readonly string $phone,
        public readonly string $alert_phone,
        public readonly string $otp
    ) {
    }

    public static function fromModel(User|Customer $user, string $otp): self
    {
        return new self(
            $user->phone,
            $user->alert_phone ?? $user->phone,
            $otp
        );
    }
}
