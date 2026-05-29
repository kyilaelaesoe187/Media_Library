<?php

namespace App\DTO;

final class UserResponseDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $username,
        public readonly string $email
    ) {}
}