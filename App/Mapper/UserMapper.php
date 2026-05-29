<?php

namespace App\Mapper;

use App\Model\User;
use App\DTO\LoginDTO;
use App\DTO\RegisterDTO;

class UserMapper
{
    /*
    |--------------------------------------------------------------------------
    | MODEL → ARRAY (response/session)
    |--------------------------------------------------------------------------
    */
    public function toArray(User $user): array
    {
        return [
            'id' => $user->id(),
            'username' => $user->username(),
            'email' => $user->email(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | MODEL → LOGIN DTO
    |--------------------------------------------------------------------------
    */
    public function toLoginDTO(array $data): LoginDTO
    {
        return new LoginDTO(
            email: $data['email'] ?? null,
            // password: $data['password']?? null
            password: ($data['password'])
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MODEL → REGISTER DTO
    |--------------------------------------------------------------------------
    */
    public function toRegisterDTO(array $data): RegisterDTO
    {
        return new RegisterDTO(
            username: $data['username'],
            email: $data['email'],
            password: $data['password']
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DTO → MODEL (REGISTER FLOW)
    |--------------------------------------------------------------------------
    */
    public function fromRegisterDTO(RegisterDTO $dto): User
    {
        return new User(
            null,
            $dto->username,
            $dto->email,
            password_hash($dto->password, PASSWORD_DEFAULT)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DTO → MODEL (LOGIN FLOW - optional if needed)
    |--------------------------------------------------------------------------
    */
    public function fromLoginDTO(LoginDTO $dto): array
    {
        return [
            'email' => $dto->email,
            'password' => $dto->password
        ];
    }
}
