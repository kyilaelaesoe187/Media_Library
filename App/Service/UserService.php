<?php

namespace App\Service;

use App\Contract\UserRepositoryInterface;
use App\Model\User;

class UserService extends BaseService
{
    public function __construct(
        private UserRepositoryInterface $repo,
        private Validator $validator
    ) {}

    /* =========================
     * LOGIN
     * ========================= */

    public function login(
        string $email,
        string $password
    ): array {

        // VALIDATION
        $this->validator->validate(
            User::loginRules(),
            [
                'email' => $email,
                'password' => $password
            ]
        );

        if ($this->validator->fails()) {

            return $this->error(
                'Validation failed',
                [
                    'errors' => $this->validator->errors()
                ]
            );
        }

        // FIND USER
        $user = $this->repo->findByEmail($email);

        if (!$user) {
            return $this->error('Invalid email');
        }

        // VERIFY PASSWORD
        if (!password_verify(
            $password,
            $user['password']
        )) {

            return $this->error('Wrong password');
        }

        // SUCCESS
        return $this->success(
            'Login successful',
            [
                'user' => $this->formatUser($user)
            ]
        );
    }

    /* =========================
     * REGISTER
     * ========================= */

    public function register(
        string $username,
        string $email,
        string $password
    ): array {

        // VALIDATION
        $this->validator->validate(
            User::registerRules(),
            [
                'username' => $username,
                'email' => $email,
                'password' => $password
            ]
        );

        if ($this->validator->fails()) {

            return $this->error(
                'Validation failed',
                [
                    'errors' => $this->validator->errors()
                ]
            );
        }

        // CHECK EXISTING EMAIL
        if ($this->repo->findByEmail($email)) {
            return $this->error('Email already exists');
        }

        // HASH PASSWORD
        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        // CREATE USER
        $ok = $this->repo->create([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword
        ]);

        return $ok
            ? $this->success('Registration successful')
            : $this->error('Registration failed');
    }

    /* =========================
     * SAFE USER OUTPUT
     * ========================= */

    private function formatUser(array $user): array
    {
        return [

            'id' => $user['id'],

            'username' => $user['username'],

            'email' => $user['email']
        ];
    }
}
