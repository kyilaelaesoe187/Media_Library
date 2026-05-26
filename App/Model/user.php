<?php

namespace App\Model;

class User
{
    /* =========================
     * LOGIN VALIDATION
     * ========================= */

    public static function loginRules(): array
    {
        return [

            'email' => [
                'required' => true,
                'email' => true,
            ],

            'password' => [
                'required' => true,
            ],
        ];
    }

    /* =========================
     * REGISTER VALIDATION
     * ========================= */

    public static function registerRules(): array
    {
        return [

            'username' => [
                'required' => true,
                'min' => 3,
                'max' => 50,
            ],

            'email' => [
                'required' => true,
                'email' => true,
                'max' => 100,
            ],

            'password' => [
                'required' => true,
                'min' => 6,
                'password_strength' => true,
            ],
        ];
    }
}
