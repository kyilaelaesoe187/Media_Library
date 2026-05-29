<?php

namespace App\Service;

use App\Contract\UserRepositoryInterface;
use App\DTO\LoginDTO;
use App\DTO\RegisterDTO;
use App\Mapper\UserMapper;
// use App\Factory\UserFactory;

class UserService extends BaseService
{
    public function __construct(
        private UserRepositoryInterface $repo,
        private UserMapper $mapper
        // private UserFactory $factory
    ) {}

    public function login(LoginDTO $dto): array
    {
        $user = $this->repo->findByEmail($dto->email);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Invalid email'
            ];
        }
        
        

        if (!$user->verifyPassword($dto->password)) {
            return [
                'success' => false,
                'message' => 'Wrong password'
            ];
        }
        // var_dump($user->passwordHash());
        // var_dump($dto->password);
        // exit;
        return [
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $this->mapper->toArray($user)
            ]
        ];
    }

    public function register(RegisterDTO $dto): array
    {
        if ($this->repo->findByEmail($dto->email)) {
            return [
                'success' => false,
                'message' => 'Email already exists'
            ];
        }

        $user = $this->mapper->fromRegisterDTO($dto);

        $this->repo->create($user);

        return [
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'user' => $this->mapper->toArray($user)
            ]
        ];
    }

    public function findUser(int $id): array
    {
        $user = $this->repo->findById($id);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found'
            ];
        }

        return [
            'success' => true,
            'data' => [
                'user' => $this->mapper->toArray($user)
            ]
        ];
    }

    public function allUsers(): array
    {
        $users = $this->repo->findAll();

        return [
            'success' => true,
            'data' => [
                'users' => array_map(
                    fn($user) => $this->mapper->toArray($user),
                    $users
                )
            ]
        ];
    }

    public function deleteUser(int $id): array
    {
        $user = $this->repo->findById($id);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found'
            ];
        }

        $this->repo->delete($id);

        return [
            'success' => true,
            'message' => 'User deleted'
        ];
    }
}
