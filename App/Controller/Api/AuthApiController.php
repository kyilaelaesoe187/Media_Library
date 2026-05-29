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
        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        $dto = new LoginDTO(
            email: $data['email'] ?? '',
            password: $data['password'] ?? ''
        );

        $response = $this->service
            ->login($dto);

        if ($response->success) {

            $_SESSION['user'] = [
                'id' => $response->data->id,
                'username' => $response->data->username,
                'email' => $response->data->email
            ];
        }

        $this->json(
            $response->toArray()
        );
    }

    public function register(): void
    {
        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        $dto = new RegisterDTO(
            username: $data['username'] ?? '',
            email: $data['email'] ?? '',
            password: $data['password'] ?? ''
        );

        $response = $this->service
            ->register($dto);

        $this->json(
            $response->toArray()
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
