<?php

namespace App\Service;

use App\Contract\UserRepositoryInterface;
use App\DTO\LoginDTO;
use App\DTO\RegisterDTO;
use App\Mapper\UserMapper;
use App\Response\ApiResponse;

final class UserService
{
    public function __construct(
        private UserRepositoryInterface $repo
    ) {}

    public function login(
        LoginDTO $dto
    ): ApiResponse {

        $user = $this->repo
            ->findByEmail($dto->email);

        if (!$user) {
            return ApiResponse::error(
                'Invalid email'
            );
        }

        if (
            !$user->verifyPassword(
                $dto->password
            )
        ) {
            return ApiResponse::error(
                'Wrong password'
            );
        }

        return ApiResponse::success(
            'Login successful',
            UserMapper::toResponseDTO($user)
        );
    }

    public function register(
        RegisterDTO $dto
    ): ApiResponse {

        /*
    |--------------------------------------------------------------------------
    | CHECK EXISTING EMAIL
    |--------------------------------------------------------------------------
    */

        if (
            $this->repo->findByEmail(
                $dto->email
            )
        ) {

            return ApiResponse::error(
                'Email already exists'
            );
        }

        /*
    |--------------------------------------------------------------------------
    | CREATE DOMAIN MODEL
    |--------------------------------------------------------------------------
    */

        $user = UserMapper::fromRegisterDTO(
            $dto
        );

        /*
    |--------------------------------------------------------------------------
    | SAVE + GET PERSISTED ENTITY
    |--------------------------------------------------------------------------
    */

        $savedUser = $this->repo
            ->create($user);

        /*
    |--------------------------------------------------------------------------
    | RESPONSE
    |--------------------------------------------------------------------------
    */

        return ApiResponse::success(
            'Registration successful',
            UserMapper::toResponseDTO(
                $savedUser
            )
        );
    }
}
