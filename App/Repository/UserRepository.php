<?php

namespace App\Repository;

use App\Contract\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?array
    {
        return $this->first('sp_get_user_by_id', [$id]);
    }

    public function findAll(?int $limit = null, int $offset = 0): array
    {
        return $this->procedure('sp_get_all_users', [$limit, $offset]);
    }

    public function create(array $data): bool
    {
        $this->procedure('sp_create_user', [
            $data['username'],
            $data['email'],
            $data['password']
        ]);

        return true;
    }

    public function update(int $id, array $data): bool
    {
        $this->procedure('sp_update_user', array_merge([$id], $data));
        return true;
    }

    public function delete(int $id): bool
    {
        $this->procedure('sp_delete_user', [$id]);
        return true;
    }

    public function findByEmail(string $email): ?array
    {
        return $this->first('sp_get_user_by_email', [$email]);
    }
}
