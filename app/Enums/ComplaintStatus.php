<?php

namespace App\Enums;

enum ComplaintStatus: int
{
    case PENDING = 1;
    case ALLOCATED = 2;
    case CLOSED = 3;
    case REOPENED = 4;

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
            'REOPENED' => self::REOPENED
        };
    }
}
