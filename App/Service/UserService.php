<?php

namespace App\Service;

use App\Contract\UserRepositoryInterface;
use App\Model\User;

class UserService extends BaseService
{
    public function __construct(
        private UserRepositoryInterface $repo
    ) {}

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(
        string $email,
        string $password
    ): array {

        $user = $this->repo
            ->findByEmail($email);

        if (!$user) {
            return $this->error(
                'Invalid email'
            );
        }

        if (
            !$user->verifyPassword($password)
        ) {
            return $this->error(
                'Wrong password'
            );
        }

        return $this->success(
            'Login successful',
            [
                'user' => $user->toResponse()
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */

    public function register(
        string $username,
        string $email,
        string $password
    ): array {

        if (
            $this->repo->findByEmail($email)
        ) {
            return $this->error(
                'Email already exists'
            );
        }

        $user = new User(
            null,
            $username,
            $email,
            password_hash(
                $password,
                PASSWORD_DEFAULT
            )
        );

        $ok = $this->repo->create($user);

        return $ok
            ? $this->success(
                'Registration successful',
                [
                    'user' => $user->toResponse()
                ]
            )
            : $this->error(
                'Registration failed'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | GET USER
    |--------------------------------------------------------------------------
    */

    public function findUser(
        int $id
    ): array {

        $user = $this->repo
            ->findById($id);

        if (!$user) {
            return $this->error(
                'User not found'
            );
        }

        return $this->success(
            'User found',
            [
                'user' => $user->toResponse()
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | GET ALL USERS
    |--------------------------------------------------------------------------
    */

    public function allUsers(): array
    {
        $users = $this->repo
            ->findAll();

        return $this->success(
            'Users retrieved',
            [
                'users' => array_map(
                    fn(User $user)
                        => $user->toResponse(),
                    $users
                )
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE USER
    |--------------------------------------------------------------------------
    */

    public function deleteUser(
        int $id
    ): array {

        $user = $this->repo
            ->findById($id);

        if (!$user) {
            return $this->error(
                'User not found'
            );
        }

        $this->repo->delete($id);

        return $this->success(
            'User deleted'
        );
    }
}