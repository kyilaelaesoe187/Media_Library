<?php

namespace App\Contract;

use App\Model\User;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;

    public function findByEmail(string $email): ?User;

    public function findAll(
        ?int $limit = null,
        int $offset = 0
    ): array;

    public function create(User $user): ?User;

    public function update(User $user): bool;

    public function delete(int $id): bool;
}
