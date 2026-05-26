<?php

namespace App\Contract;

interface BaseInterface
{
    public function findById(
        int $id
    ): ?array;

    public function findAll(
        ?int $limit = null,
        int $offset = 0
    ): array;

    public function create(
        array $data
    ): bool;

    public function update(
        int $id,
        array $data
    ): bool;

    public function delete(
        int $id
    ): bool;
}
