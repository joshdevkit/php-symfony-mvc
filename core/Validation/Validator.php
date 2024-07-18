<?php

namespace Core\Validation;

use App\Http\Models\User;
use Core\Exceptions\ValidationException;

class Validator
{
    protected array $errors = [];

    public function validate(array $input, array $rules)
    {
        foreach ($rules as $field => $rule) {
            $rulesArray = explode('|', $rule);
            foreach ($rulesArray as $rule) {
                $ruleDetails = explode(':', $rule);
                $ruleName = $ruleDetails[0];
                $ruleValue = $ruleDetails[1] ?? null;

                switch ($ruleName) {
                    case 'required':
                        $this->validateRequired($field, $input[$field] ?? null);
                        break;
                    case 'min':
                        $this->validateMin($field, $input[$field] ?? null, $ruleValue);
                        break;
                    case 'max':
                        $this->validateMax($field, $input[$field] ?? null, $ruleValue);
                        break;
                    case 'confirm_password':
                        $this->validateConfirmPassword($field, $input['password'] ?? null, $input[$field] ?? null);
                        break;
                    case 'email':
                        if (!empty($input[$field])) {
                            $this->validateEmail($field, $input[$field]);
                        }
                        break;
                    case 'unique':
                        if (!empty($input[$field])) {
                            $this->validateUnique($field, $input[$field], $ruleValue);
                        }
                        break;
                    default:
                        throw new \InvalidArgumentException('Invalid validation rule: ' . $ruleName);
                }
            }
        }

        if (!empty($this->errors)) {
            throw new ValidationException($this->errors);
        }

        return true;
    }

    public function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    protected function validateRequired(string $field, $value): void
    {
        if (empty($value)) {
            $this->addError($field, 'The ' . $this->formatFieldName($field) . ' is required.');
        }
    }

    protected function validateMin(string $field, $value, $min): void
    {
        if (!empty($value) && strlen($value) < $min) {
            $this->addError($field, 'The ' . $this->formatFieldName($field) . ' must be at least ' . $min . ' characters.');
        }
    }

    protected function validateMax(string $field, $value, $max): void
    {
        if (!empty($value) && strlen($value) > $max) {
            $this->addError($field, 'The ' . $this->formatFieldName($field) . ' must be less than ' . $max . ' characters.');
        }
    }

    protected function validateConfirmPassword(string $field, $password, $confirmPassword): void
    {
        if (!empty($password) && $password !== $confirmPassword) {
            $this->addError($field, 'The ' . $this->formatFieldName($field) . ' does not match.');
        }
    }

    protected function validateEmail(string $field, $value): void
    {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, 'The ' . $this->formatFieldName($field) . ' must be a valid email address.');
        }
    }

    protected function validateUnique(string $field, $value, $table): void
    {
        if (!empty($value)) {
            $result = User::where($field, $value);
            if ($result) {
                $this->addError($field, 'This ' . $this->formatFieldName($field) . ' has already been taken.');
            }
        }
    }

    protected function formatFieldName(string $field): string
    {
        return ucwords(str_replace('_', ' ', $field));
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
