<?php

namespace App\Controller\Api;

use App\Controller\BaseController;
use App\Service\UserService;

class AuthApiController extends BaseController
{
    private UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * LOGIN API
     */
    public function login(): void
    {
        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        $result = $this->service->login(
            $data['email'] ?? '',
            $data['password'] ?? ''
        );

        // Save session after successful login
        if ($result['success']) {

            $_SESSION['user'] = $result['user'];
        }

        $this->json($result);
    }

    /**
     * REGISTER API
     */
    public function register(): void
    {
        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        $result = $this->service->register(
            $data['username'] ?? '',
            $data['email'] ?? '',
            $data['password'] ?? ''
        );

        $this->json($result);
    }

    /**
     * LOGOUT API
     */
    public function logout(): void
    {
        session_unset();

        session_destroy();

        $this->json([
            'success' => true,
            'message' => 'Logout successful'
        ]);
    }
}