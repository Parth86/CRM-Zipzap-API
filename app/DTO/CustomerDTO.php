<?php

namespace App\DTO;

use App\Models\Customer;

class CustomerDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $phone,
        public readonly string $alert_phone,
        public readonly ?string $password
    ) {
    }

    public static function fromModel(Customer $customer): self
    {
        return new self(
            $customer->name,
            $customer->phone,
            $customer->alert_phone ?? $customer->phone,
            $customer->original_password
        );
    }
}
