<?php

namespace App\DTO;

use App\Models\Query;

class QueryDTO
{
    public function __construct(
        public readonly string $product,
    ) {
    }

    public static function fromModel(Query $query): self
    {
        return new QueryDTO($query->product);
    }
}
