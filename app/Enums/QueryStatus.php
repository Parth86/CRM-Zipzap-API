<?php

namespace App\Enums;

enum QueryStatus: int
{
    case OPEN = 0;
    case CLOSED = 1;

    public function label(): string
    {
        return $this->name;
    }

    public function isClosed(): bool
    {
        return $this === self::CLOSED;
    }

    public function isOpen(): bool
    {
        return $this === self::OPEN;
    }
}
