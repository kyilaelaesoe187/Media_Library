<?php

namespace App\Service;

class Validator
{
    private array $errors = [];

    public function errors(): array
    {
        return $this->errors;
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function validate(
        array $rules,
        array $data
    ): self {

        $this->errors = [];

        foreach ($rules as $field => $ruleSet) {

            $value = $data[$field] ?? null;

            foreach ($ruleSet as $rule => $ruleValue) {

                if (is_int($rule)) {
                    $rule = $ruleValue;
                    $ruleValue = null;
                }

                $this->apply(
                    $field,
                    $value,
                    $rule,
                    $ruleValue
                );

                // STOP NEXT RULES
                if (isset($this->errors[$field])) {
                    break;
                }
            }
        }

        return $this;
    }

    private function apply(
        string $field,
        $value,
        string $rule,
        $ruleValue = null
    ): void {

        // REQUIRED
        if (
            $rule === 'required' &&
            empty($value)
        ) {

            $this->errors[$field] =
                ucfirst($field) . ' is required';

            return;
        }

        // EMAIL
        if (
            $rule === 'email' &&
            !empty($value) &&
            !filter_var($value, FILTER_VALIDATE_EMAIL)
        ) {

            $this->errors[$field] =
                'Invalid email format';

            return;
        }

        // MIN
        if (
            $rule === 'min' &&
            strlen((string) $value) < $ruleValue
        ) {

            $this->errors[$field] =
                ucfirst($field)
                . " must be at least {$ruleValue} characters";

            return;
        }

        // MAX
        if (
            $rule === 'max' &&
            strlen((string) $value) > $ruleValue
        ) {

            $this->errors[$field] =
                ucfirst($field)
                . " must not exceed {$ruleValue} characters";

            return;
        }

        // PASSWORD STRENGTH
        if (
            $rule === 'password_strength' &&
            !empty($value)
        ) {

            $hasUppercase =
                preg_match('/[A-Z]/', $value);

            $hasNumber =
                preg_match('/[0-9]/', $value);

            $hasSpecial =
                preg_match('/[\W]/', $value);

            if (
                !$hasUppercase ||
                !$hasNumber ||
                !$hasSpecial
            ) {

                $this->errors[$field] =
                    'Password must contain uppercase letter, number and special character';
            }
        }
    }
}
