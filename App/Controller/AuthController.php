<?php

namespace App\Controller;

use App\Service\UserService;

class AuthController extends BaseController
{
    public function __construct(
        private UserService $service
    ) {}

    public function login(): void
    {
        $message = null;
        $errors = [];

        if ($this->isPost()) {

            $result = $this->service->login(
                $this->post('email'),
                $this->post('password')
            );

            // if ($result['success']) {


            //     $this->session('user_id', $result['user']['id']);
            //     $this->redirect('?page=home');
            // }
            if ($result['success']) {

                // SAVE SESSION
                $_SESSION['user'] = $result['user'];

                $_SESSION['user_id'] = $result['user']['id'];

                // DEBUG
                // REMOVE AFTER TEST
                /*
            echo '<pre>';
            print_r($_SESSION);
            exit;
            */

                // REDIRECT
                header('Location: ?page=home');
                exit;
            }

            $this->render('auth/login', [
                'message' => $result['message'],
                'errors' => $result['errors'] ?? []
            ]);
        }

        $this->render('auth/login', compact('message', 'errors'));
    }

    public function register(): void
    {
        $message = null;
        $errors = [];

        if ($this->isPost()) {

            $result = $this->service->register(
                $this->post('username'),
                $this->post('email'),
                $this->post('password')
            );

            if ($result['success']) {
                $this->redirect('?page=login');
            }

            $message = $result['message'];
            $errors = $result['errors'] ?? [];
        }

        $this->render('auth/register', compact('message', 'errors'));
    }

    public function logout(): void
    {
        $this->destroySession();
        $this->redirect('?page=login');
    }
}
