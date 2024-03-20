<?php

namespace App\Enums;

enum ComplaintStatus: int
{
    case PENDING = 1;
    case ALLOCATED = 2;
    case CLOSED = 3;
    case REOPENED = 4;
    case REALLOCATED = 5;

    public function label(): string
    {
        return $this->name;
    }

    public static function createFromName(string $name): self
    {
        return match ($name) {
            'PENDING' => self::PENDING,
            'ALLOCATED' => self::ALLOCATED,
            'CLOSED' => self::CLOSED,
            'REOPENED' => self::REOPENED,
            'REALLOCATED' => self::REALLOCATED
        };
    }

    public function isClosed(): bool
    {
        return $this === self::CLOSED;
    }

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }
}
