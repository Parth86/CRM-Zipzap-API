<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

trait HasUuid
{
    /**
     * Find a model instance by UUID.
     *
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

    public function scopeWhereUuid(Builder $query, string $uuid)
    {
        return $query->where('uuid', $uuid);
    }

    public static function findIdByUuid(string $uuid): int
    {
        return self::where('uuid', $uuid)->select('id')->firstOrFail()?->id;
    }
}
