<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait GeneratePassword
{
    public static function bootGeneratePassword()
    {
        $password = Str::random(10);

        static::creating(function (self $model) use ($password) {
            $model->password = $password;
        });

        static::created(function (self $model) use ($password) {
            $model->original_password = $password;
        });
    }
}
