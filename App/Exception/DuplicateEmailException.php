<?php

namespace App\Exception;

final class DuplicateEmailException
extends AppException
{
    public static function alreadyExists(): self
    {
        return new self(
            'Email already exists'
        );
    }
}
