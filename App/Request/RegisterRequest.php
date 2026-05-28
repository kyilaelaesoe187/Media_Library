<?php

namespace App\Request;

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
}
