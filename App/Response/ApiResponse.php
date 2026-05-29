<?php

namespace App\Response;

final class ApiResponse
{
    public function __construct(
        public readonly bool $success,
        public readonly string $message,
        public readonly mixed $data = null
    ) {}

    public static function success(
        string $message,
        mixed $data = null
    ): self {
        return new self(true, $message, $data);
    }

    public static function error(
        string $message,
        mixed $data = null
    ): self {
        return new self(false, $message, $data);
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'data' => $this->data
        ];
    }
}
