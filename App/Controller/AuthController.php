<?php

namespace App\Controller;

use App\Request\LoginRequest;
use App\Request\RegisterRequest;
use App\Service\UserService;
use App\Service\Validator;

class AuthController extends BaseController
{
    public function __construct(
        private UserService $service,
        private Validator $validator
    ) {}

    public function login(): void
    {
        $message = null;
        $errors = [];

        if ($this->isPost()) {
// var_dump($this->isPost());
// exit;
            $request = new LoginRequest();

            if (!$request->validate()) {

                $this->render('auth/login', [
                    'message' => 'Validation failed',
                    'errors' => $request->errors()
                ]);
                return;
            }

            $data = $request->validated();
// var_dump($data);
// exit;
            $result = $this->service->login(
                $data['email'],
                $data['password']
            );
// var_dump($result);
// exit;
            if ($result['success']) {
// var_dump($result);
// exit;
                $user = $result['data']['user'] ?? null;
// var_dump($user);
// exit;
                if ($user) {

                    $_SESSION['user'] = $user;
                    $_SESSION['user_id'] = $user['id'];
                }

                $this->redirect('?page=home');
            }

            $message = $result['message'];
        }

        $this->render('auth/login', compact('message', 'errors'));
    }

    public function register(): void
    {
        $message = null;
        $errors = [];

        if ($this->isPost()) {

            $request = new RegisterRequest();

            if (!$request->validate()) {
                $this->render('auth/register', [
                    'message' => 'Validation failed',
                    'errors' => $request->errors()
                ]);
                return;
            }

            $data = $request->validated();

            $result = $this->service->register(
                $data['username'],
                $data['email'],
                $data['password']
            );

            if ($result['success']) {
                $this->redirect('?page=login');
            }

            $message = $result['message'];
        }

        $this->render('auth/register', compact('message', 'errors'));
    }

    public function logout(): void
    {
        $this->destroySession();
        $this->redirect('?page=login');
    }
}
