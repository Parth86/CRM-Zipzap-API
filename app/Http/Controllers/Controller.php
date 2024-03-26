<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function response(array $data, string $message, bool $status = true, int $code = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'status' => $status,
            'message' => $message,
        ], $code);
    }
}
