<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    /**
     * Find a model instance by UUID.
     *
     * @param string $uuid
     * @return ?self
     */
    public static function findByUuid(string $uuid): ?self
    {
        return self::where('uuid', $uuid)->firstOrFail();
    }

    public static function bootHasUuid()
    {
        static::creating(function (self $model) {
            $model->uuid = Str::uuid();
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
