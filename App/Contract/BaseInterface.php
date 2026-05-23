<?php

namespace App\Contract;

interface BaseInterface
{
    public function count(
        array $filters = []
    ): int;

    public function getAll(
        ?int $limit = null,
        int $offset = 0
    ): array;

    public function getById(
        int $id
    ): ?array;
}