<?php

namespace App\Request;

use App\Service\Validator;

abstract class FormRequest
{
    protected array $errors = [];
    protected array $validated = [];
    protected Validator $validator;

    public function __construct()
    {
        $this->validator = new Validator();
    }

    abstract public function rules(): array;

    public function data(): array
    {
        return $_POST;
    }

    public function validate(): bool
    {
        $this->validator->validate(
            $this->rules(),
            $this->data()
        );

        $this->errors = $this->validator->errors();

        if (!empty($this->errors)) {
            return false;
        }

        $this->validated = array_intersect_key(
            $this->data(),
            array_flip(array_keys($this->rules()))
        );

        return true;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function validated(): array
    {
        return $this->validated;
    }
}
