<?php

namespace App\Exception;

final class AuthenticationException
extends AppException
{
    public static function invalidEmail(): self
    {
        return new self(
            'Invalid email'
        );
    }

    public static function invalidPassword(): self
    {
        return new self(
            'Wrong password'
        );
    }
}
