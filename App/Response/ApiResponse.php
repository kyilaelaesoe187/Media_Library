<?php

namespace App\Response;

class ApiResponse
{
    public static function success(string $message, mixed $data = null): array
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];
    }

    public static function error(string $message, mixed $errors = null): array
    {
        return [
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ];
    }
}
