<?php

namespace App\DTO;

use App\Models\Complaint;

class ComplaintDTO
{
    public function __construct(
        public readonly string $product,
    ) {
    }

    public static function fromModel(Complaint $query): self
    {
        return new ComplaintDTO($query->product);
    }
}
