<?php

namespace App\Enums;

enum ComplaintStatus: int
{
    case PENDING = 1;
    case ALLOCATED = 2;
    case CLOSED = 3;

    public function label(): string
    {
        return $this->name;
    }
}
