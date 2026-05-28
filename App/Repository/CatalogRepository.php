<?php

namespace App\Repository;

use App\Contract\CatalogRepositoryInterface;

class CatalogRepository extends BaseRepository implements CatalogRepositoryInterface
{
    protected string $getByIdProcedure = 'sp_get_item_full_detail';

    // ------------------------
    // REQUIRED BASE METHODS
    // ------------------------

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "CALL {$this->getByIdProcedure}(?)"
        );

        $stmt->execute([$id]);

        /*
    |--------------------------------------------------------------------------
    | FIRST RESULT SET
    |--------------------------------------------------------------------------
    */

        $item = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$item) {

            $stmt->closeCursor();

            return null;
        }

        /*
    |--------------------------------------------------------------------------
    | SECOND RESULT SET
    |--------------------------------------------------------------------------
    */

        $stmt->nextRowset();

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {

            $role = strtolower($row['role']);

            $item[$role][] = $row['fullname'];
        }

        $stmt->closeCursor();

        return $item;
    }

    public function findAll(?int $limit = null, int $offset = 0): array
    {
        return $this->procedure('sp_get_full_catalog', [$limit, $offset]);
    }

    public function create(array $data): bool
    {
        $this->procedure('sp_create_catalog', $data);
        return true;
    }

    public function update(int $id, array $data): bool
    {
        $this->procedure('sp_update_catalog', array_merge([$id], $data));
        return true;
    }

    public function delete(int $id): bool
    {
        $this->procedure('sp_delete_catalog', [$id]);
        return true;
    }

    // ------------------------
    // CUSTOM METHODS
    // ------------------------

    public function findByCategory(string $category, ?int $limit = null, int $offset = 0): array
    {
        return $this->procedure('sp_get_catalog', [$category, $limit, $offset]);
    }

    public function search(string $search, ?string $category = null, ?int $limit = null, int $offset = 0): array
    {
        return $this->procedure('sp_search_catalog', [$search, $category, $limit, $offset]);
    }

    public function findRandom(): array
    {
        return $this->query("SELECT * FROM view_random");
    }

    public function count(?string $category = null, ?string $search = null): int
    {
        $result = $this->first('sp_search_catalog_count', [
            $search,
            $category
        ]);

        return (int) ($result['count'] ?? 0);
    }
}
