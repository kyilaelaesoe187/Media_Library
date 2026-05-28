<?php

namespace App\Mapper;

use App\Model\User;
use App\DTO\UserDTO;

class UserMapper
{
    public static function toDTO(User $user): UserDTO
    {
        return new UserDTO(
            id: $user->id(),
            username: $user->username(),
            email: $user->email()
        );
    }

    public static function toArray(User $user): array
    {
        return [
            'id' => $user->id(),
            'username' => $user->username(),
            'email' => $user->email()
        ];
    }
}
