<?php

namespace App\Contract;

interface CatalogRepositoryInterface
extends BaseInterface
{
    public function count(
        ?string $category = null,
        ?string $search = null
    ): int;

    public function findByCategory(
        string $category,
        ?int $limit = null,
        int $offset = 0
    ): array;

    public function search(
        string $search,
        ?string $category = null,
        ?int $limit = null,
        int $offset = 0
    ): array;

    public function findRandom(): array;
}
