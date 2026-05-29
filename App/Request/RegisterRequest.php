<?php

namespace App\Request;

use App\DTO\RegisterDTO;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => ['required', 'min' => 3, 'max' => 50],
            'email' => ['required', 'email'],
            'password' => ['required', 'min' => 6, 'password_strength']
        ];
    }

    public function toDTO(): RegisterDTO
    {
        $data = $this->validated();

        return new RegisterDTO(
            username: $data['username'],
            email: $data['email'],
            password: $data['password']
        );
    }
}
