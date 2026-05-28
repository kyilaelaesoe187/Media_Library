<?php

namespace App\Controller\Api;

use App\Controller\BaseController;
use App\Service\UserService;

class AuthApiController extends BaseController
{
    public function __construct(
        private UserService $service
    ) {}

    public function login(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->service->login(
            $data['email'] ?? '',
            $data['password'] ?? ''
        );

        if ($result['success']) {
            $_SESSION['user'] = $result['data']['user'];
        }

        $this->json($result);
    }

    public function register(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->service->register(
            $data['username'] ?? '',
            $data['email'] ?? '',
            $data['password'] ?? ''
        );

        $this->json($result);
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
