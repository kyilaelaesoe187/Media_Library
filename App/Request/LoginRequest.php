<?php

namespace App\Request;

use App\DTO\LoginDTO;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required']
        ];
    }
   
}
