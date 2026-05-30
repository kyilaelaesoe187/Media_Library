<?php

namespace App\Controller;

use App\DTO\LoginDTO;
use App\DTO\RegisterDTO;
use App\Request\LoginRequest;
use App\Request\RegisterRequest;
use App\Service\UserService;

class AuthController extends BaseController
{
    public function __construct(
        private UserService $service
    ) {}

    public function login(): void
    {
        $this->executeWithView(function () {

            if (!$this->isPost()) {
                $this->render('auth/login');
                return;
            }

            $request = new LoginRequest();

            if (!$request->validate()) {
                $this->render('auth/login', [
                    'errors' => $request->errors()
                ]);
                return;
            }

            $dto = new LoginDTO(...$request->validated());

            $user = $this->service->login($dto);

            $_SESSION['user'] = [
                'id'       => $user->id,
                'username' => $user->username,
                'email'    => $user->email,
            ];
            // echo '<pre>';
            // print_r($_SESSION);
            // echo '</pre>';
            // exit;
            $this->redirect('?page=home');
        });
    }

    public function register(): void
    {
        $this->execute(

            action: function () {

                if (!$this->isPost()) {

                    $this->render(
                        'auth/register'
                    );

                    return;
                }

                $request = new RegisterRequest();

                if (!$request->validate()) {

                    $this->render(
                        'auth/register',
                        [
                            'message' =>
                            'Validation failed',

                            'errors' =>
                            $request->errors()
                        ]
                    );

                    return;
                }

                $data = $request->validated();

                $dto = new RegisterDTO(
                    username: $data['username'],
                    email: $data['email'],
                    password: $data['password']
                );

                $this->service
                    ->register($dto);

                $this->redirect('?page=login');
            },

            view: 'auth/register'
        );
    }

    public function logout(): void
    {
        $this->destroySession();
        $this->redirect('?page=login');
    }
}
