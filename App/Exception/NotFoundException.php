<?php

namespace App\Exception;

final class NotFoundException
extends AppException
{
    public static function user(): self
    {
        return new self(
            'User not found'
        );
    }

    public static function item(): self
    {
        return new self(
            'Item not found'
        );
    }
}
