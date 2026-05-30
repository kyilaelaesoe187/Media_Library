<?php

namespace App\Service;

use App\Contract\UserRepositoryInterface;
use App\DTO\LoginDTO;
use App\DTO\RegisterDTO;
use App\DTO\UserResponseDTO;
use App\Exception\AuthenticationException;
use App\Exception\DuplicateEmailException;
use App\Exception\NotFoundException;
use App\Mapper\UserMapper;
use App\Model\User;

final class UserService
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
        LoginDTO $dto
    ): UserResponseDTO {

        $user = $this->repo
            ->findByEmail($dto->email);

        if (!$user) {

            throw AuthenticationException
                ::invalidEmail();
        }

        if (
            !$user->verifyPassword(
                $dto->password
            )
        ) {

            throw AuthenticationException
                ::invalidPassword();
        }

        return UserMapper
            ::toResponseDTO($user);
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */

    public function register(
        RegisterDTO $dto
    ): UserResponseDTO {

        if (
            $this->repo->findByEmail(
                $dto->email
            )
        ) {

            throw DuplicateEmailException
                ::alreadyExists();
        }

        $user = UserMapper
            ::fromRegisterDTO($dto);

        $savedUser = $this->repo
            ->create($user);

        return UserMapper
            ::toResponseDTO($savedUser);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND USER
    |--------------------------------------------------------------------------
    */

    public function findUser(
        int $id
    ): UserResponseDTO {

        $user = $this->repo
            ->findById($id);

        if (!$user) {

            throw NotFoundException
                ::user();
        }

        return UserMapper
            ::toResponseDTO($user);
    }

    /*
    |--------------------------------------------------------------------------
    | ALL USERS
    |--------------------------------------------------------------------------
    */

    public function allUsers(): array
    {
        $users = $this->repo
            ->findAll();

        return array_map(

            fn(User $user)

            => UserMapper
                ::toResponseDTO($user),

            $users
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE USER
    |--------------------------------------------------------------------------
    */

    public function deleteUser(
        int $id
    ): void {

        $user = $this->repo
            ->findById($id);

        if (!$user) {

            throw NotFoundException
                ::user();
        }

        $this->repo->delete($id);
    }
}
