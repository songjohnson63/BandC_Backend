<?php

namespace App\Helpers;

class ApiResponseHelper
{
    public static function success($data, $message = 'OK', $statusCode = 200)
    {
        return response()->json([
            'status' => $statusCode,
            'status_code' => 'success',
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    public static function error($message, $statusCode = 400)
    {
        return response()->json([
            'status' => $statusCode,
            'status_code' => 'error',
            'message' => $message,
            'data' => null,
        ], $statusCode);
    }
}
