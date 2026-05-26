<?php

namespace App\Contract;

interface UserRepositoryInterface
extends BaseInterface
{
    public function findByEmail(
        string $email
    ): ?array;
}
