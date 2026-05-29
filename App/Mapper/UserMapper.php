<?php

namespace App\Mapper;

use App\DTO\RegisterDTO;
use App\DTO\UserResponseDTO;
use App\Model\User;

final class UserMapper
{
    public static function fromRegisterDTO(
        RegisterDTO $dto
    ): User {
        return new User(
            id: null,
            username: $dto->username,
            email: $dto->email,
            passwordHash: password_hash(
                $dto->password,
                PASSWORD_DEFAULT
            )
        );
    }

    public static function toResponseDTO(
        User $user
    ): UserResponseDTO {
        return new UserResponseDTO(
            id: $user->id(),
            username: $user->username(),
            email: $user->email()
        );
    }

    public static function fromArray(
        array $data
    ): User {
        return new User(
            id: (int) $data['id'],
            username: $data['username'],
            email: $data['email'],
            passwordHash: $data['password']
        );
    }
}
