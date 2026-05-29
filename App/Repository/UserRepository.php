<?php

namespace App\Repository;

use App\Contract\UserRepositoryInterface;
use App\Mapper\UserMapper;
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
            ? UserMapper::fromArray($data)
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
            ? UserMapper::fromArray($data)
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
            fn(array $row)
            => UserMapper::fromArray($row),
            $rows
        );
    }

    public function create(User $user): User
    {
        $data = $user->toPersistence();

        /*
    |--------------------------------------------------------------------------
    | SAVE USER
    |--------------------------------------------------------------------------
    */

        $this->procedure(
            'sp_create_user',
            [
                $data['username'],
                $data['email'],
                $data['password']
            ]
        );

        /*
    |--------------------------------------------------------------------------
    | RELOAD PERSISTED USER
    |--------------------------------------------------------------------------
    */

        $savedUser = $this->findByEmail(
            $data['email']
        );

        if (!$savedUser) {

            throw new \RuntimeException(
                'Failed to reload saved user.'
            );
        }

        return $savedUser;
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
}
