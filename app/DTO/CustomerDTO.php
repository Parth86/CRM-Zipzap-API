<?php

namespace App\DTO;

use App\Models\Customer;

class CustomerDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $phone
    ) {
    }

    public static function fromModel(Customer $customer): self
    {
        return new CustomerDTO($customer->name, $customer->phone);
    }
}
