<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Facade;

class ApiResponse extends Facade
{
    public function success($code = 200, $data = [], $message = 'Successful'): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
        ], $code);
    }

    public function error($code = 400, $errors = [], $message = ''): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors,
            'code' => $code,
        ], $code);
    }
}
