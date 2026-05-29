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
        $this->processForm(

            request: new LoginRequest(),

            view: 'auth/login',

            serviceAction: function (array $data) {

                return $this->service->login(

                    new LoginDTO(
                        email: $data['email'],
                        password: $data['password']
                    )
                );
            },

            onSuccess: function ($response) {

                $this->session('user', [

                    'id' => $response->data->id,

                    'username' => $response->data->username,

                    'email' => $response->data->email
                ]);

                $this->session(
                    'user_id',
                    $response->data->id
                );

                $this->redirect('?page=home');
            }
        );
    }

    public function register(): void
    {
        $this->processForm(

            request: new RegisterRequest(),

            view: 'auth/register',

            serviceAction: function (array $data) {

                return $this->service->register(

                    new RegisterDTO(
                        username: $data['username'],
                        email: $data['email'],
                        password: $data['password']
                    )
                );
            },

            onSuccess: function () {

                $this->redirect('?page=login');
            }
        );
    }

    public function logout(): void
    {
        $this->destroySession();
        $this->redirect('?page=login');
    }
}
