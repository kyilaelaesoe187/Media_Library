<?php

namespace App\Repository;

use App\Contract\UserRepositoryInterface;
use App\Model\User;

class UserRepository
    extends BaseRepository
    implements UserRepositoryInterface
{
    public function findById(int $id): ?User
    {
        $data = $this->first(
            'sp_get_user_by_id',
            [$id]
        );

        return $data
            ? $this->map($data)
            : null;
    }

    public function findByEmail(
        string $email
    ): ?User {

        $data = $this->first(
            'sp_get_user_by_email',
            [$email]
        );

        return $data
            ? $this->map($data)
            : null;
    }

    public function findAll(
        ?int $limit = null,
        int $offset = 0
    ): array {

        $rows = $this->procedure(
            'sp_get_all_users',
            [$limit, $offset]
        );

        return array_map(
            fn(array $row) => $this->map($row),
            $rows
        );
    }

    public function create(User $user): bool
    {
        $data = $user->toPersistence();

        $this->procedure(
            'sp_create_user',
            [
                $data['username'],
                $data['email'],
                $data['password']
            ]
        );

        return true;
    }

    public function update(User $user): bool
    {
        $data = $user->toPersistence();

        $this->procedure(
            'sp_update_user',
            [
                $data['id'],
                $data['username'],
                $data['email'],
                $data['password']
            ]
        );

        return true;
    }

    public function delete(int $id): bool
    {
        $this->procedure(
            'sp_delete_user',
            [$id]
        );

        return true;
    }

    private function map(array $data): User
    {
        return new User(
            $data['id'],
            $data['username'],
            $data['email'],
            $data['password']
        );
    }
}