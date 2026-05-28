<?php

namespace App\Repository;

//use App\Contract\BaseInterface;
use PDO;

abstract class BaseRepository //implements BaseInterface
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    protected function procedure(string $name, array $params = []): array
    {
        $stmt = $this->db->prepare(
            "CALL {$name}(" . implode(',', array_fill(0, count($params), '?')) . ")"
        );

        $stmt->execute($params);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt->closeCursor();

        return $data;
    }

    protected function first(string $name, array $params = []): ?array
    {
        return $this->procedure($name, $params)[0] ?? null;
    }

    protected function query(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
