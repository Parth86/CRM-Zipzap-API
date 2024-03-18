<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function response(array $data, string $message, bool $status = true, int $code = 200)
    {
        return response()->json([
            'data' => $data,
            'status' => $status,
            'message' => $message,
        ], $code);
    }
}
