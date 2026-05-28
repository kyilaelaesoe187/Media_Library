<?php

namespace App\Service;

use App\Contract\UserRepositoryInterface;
use App\Model\User;
use App\Mapper\UserMapper;
use App\Response\ApiResponse;

class UserService extends BaseService
{
    public function __construct(
        private UserRepositoryInterface $repo
    ) {}

    public function login(string $email, string $password): array
    {
        $user = $this->repo->findByEmail($email);

        if (!$user) {
            return ApiResponse::error('Invalid email');
        }

        if (!$user->verifyPassword($password)) {
            return ApiResponse::error('Wrong password');
        }

        return ApiResponse::success(
            'Login successful',
            [
                'user' => UserMapper::toArray($user)
            ]
        );
    }

    public function register(string $username, string $email, string $password): array
    {
        if ($this->repo->findByEmail($email)) {
            return ApiResponse::error('Email already exists');
        }

        $user = new User(
            null,
            $username,
            $email,
            password_hash($password, PASSWORD_DEFAULT)
        );

        $ok = $this->repo->create($user);

        if (!$ok) {
            return ApiResponse::error('Registration failed');
        }

        return ApiResponse::success(
            'Registration successful',
            [
                'user' => UserMapper::toArray($user)
            ]
        );
    }

    public function findUser(int $id): array
    {
        $user = $this->repo->findById($id);

        if (!$user) {
            return ApiResponse::error('User not found');
        }

        return ApiResponse::success(
            'User found',
            [
                'user' => UserMapper::toArray($user)
            ]
        );
    }

    public function allUsers(): array
    {
        $users = $this->repo->findAll();

        return ApiResponse::success(
            'Users retrieved',
            [
                'users' => array_map(
                    fn($user) => UserMapper::toArray($user),
                    $users
                )
            ]
        );
    }

    public function deleteUser(int $id): array
    {
        $user = $this->repo->findById($id);

        if (!$user) {
            return ApiResponse::error('User not found');
        }

        $this->repo->delete($id);

        return ApiResponse::success('User deleted');
    }
}
