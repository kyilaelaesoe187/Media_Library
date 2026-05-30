<?php

namespace App\Controller\Api;

use App\Controller\BaseController;
use App\DTO\LoginDTO;
use App\DTO\RegisterDTO;
use App\Service\UserService;

class AuthApiController
extends BaseController
{
    public function __construct(
        private UserService $service
    ) {}

    public function login(): void
    {
        $this->api(

            action: function () {

                $data = json_decode(
                    file_get_contents('php://input'),
                    true
                );

                $dto = new LoginDTO(
                    email: $data['email'] ?? '',
                    password: $data['password'] ?? ''
                );

                $user = $this->service
                    ->login($dto);

                $_SESSION['user'] = [

                    'id' => $user->id,

                    'username' => $user->username,

                    'email' => $user->email
                ];

                $_SESSION['user_id'] = $user->id;

                return [
                    'user' => $user
                ];
            }
        );
    }

    public function register(): void
    {
        $this->api(

            action: function () {

                $data = json_decode(
                    file_get_contents('php://input'),
                    true
                );

                $dto = new RegisterDTO(
                    username: $data['username'] ?? '',
                    email: $data['email'] ?? '',
                    password: $data['password'] ?? ''
                );

                $user = $this->service
                    ->register($dto);

                return [
                    'user' => $user
                ];
            }
        );
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();

        $this->json([
            'success' => true,
            'message' => 'Logout successful',
            'data' => null
        ]);
    }
}
