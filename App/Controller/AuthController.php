<?php

namespace App\Controller;

use App\Request\LoginRequest;
use App\Request\RegisterRequest;
use App\Service\UserService;
use App\Mapper\UserMapper;

class AuthController extends BaseController
{
    public function __construct(
        private UserService $service,
        private UserMapper $userMapper
    ) {}

    public function login(): void
    {
        if (!$this->isPost()) {
            $this->render('auth/login');
            return;
        }

        $this->handleRequest(
            new LoginRequest(),
            function ($data) {
// echo$data;
// exit;
                $dto = $this->userMapper->toLoginDTO($data);

                $result = $this->service->login($dto);

                $this->handleServiceResult(
                    $result,
                    function ($result) {

                        $_SESSION['user'] = $result['data']['user'];
                        $_SESSION['user_id'] = $result['data']['user']['id'];

                        $this->redirect('?page=home');
                    },
                    'auth/login'
                );
            },
            'auth/login'
        );
    }

    public function register(): void
    {
        if (!$this->isPost()) {
            $this->render('auth/register');
            return;
        }

        $this->handleRequest(
            new RegisterRequest(),
            function ($data) {

               $dto = $this->userMapper->toRegisterDTO($data);

                $result = $this->service->register($dto);
                $this->handleServiceResult(
                    $result,
                    function () {
                        $this->redirect('?page=login');
                    },
                    'auth/register'
                );
            },
            'auth/register'
        );
    }

    public function logout(): void
    {
        $this->destroySession();
        $this->redirect('?page=login');
    }
}
