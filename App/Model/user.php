<?php

namespace App\Model;

class User
{
    private ?int $id;

    private string $username;

    private string $email;

    private string $passwordHash;

    public function __construct(
        ?int $id,
        string $username,
        string $email,
        string $passwordHash
    ) {
        $this->id = $id;

        $this->changeUsername($username);

        $this->changeEmail($email);

        $this->passwordHash = $passwordHash;
    }

    /*
    |--------------------------------------------------------------------------
    | GETTERS
    |--------------------------------------------------------------------------
    */

    public function id(): ?int
    {
        return $this->id;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function email(): string
    {
        return $this->email;
    }

    /*
    |--------------------------------------------------------------------------
    | BUSINESS RULES
    |--------------------------------------------------------------------------
    */

    public function changeUsername(string $username): void
    {
        $username = trim($username);

        if ($username === '') {
            throw new \InvalidArgumentException(
                'Username is required.'
            );
        }

        $this->username = $username;
    }

    public function changeEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(
                'Invalid email.'
            );
        }

        $this->email = $email;
    }

    public function verifyPassword(
        string $plainPassword
    ): bool {
        return password_verify(
            $plainPassword,
            $this->passwordHash
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PERSISTENCE
    |--------------------------------------------------------------------------
    */

    public function toPersistence(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->passwordHash
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RESPONSE
    |--------------------------------------------------------------------------
    */

    // public function toResponse(): array
    // {
    //     return [
    //         'id' => $this->id,
    //         'username' => $this->username,
    //         'email' => $this->email
    //     ];
    // }
}
