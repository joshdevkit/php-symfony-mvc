<?php

namespace Core\Validation;

use App\Http\Models\User;
use Core\Exceptions\ValidationException;

class NewValidator
{
    protected array $validatedData = [];
    protected array $errors = [];

    public function validate(array $data, array $rules)
    {
        foreach ($rules as $key => $rule) {
            $rulesArray = explode('|', $rule);

            foreach ($rulesArray as $singleRule) {
                $this->applyRule($data, $key, $singleRule);
            }
        }

        if (!empty($this->errors)) {
            throw new ValidationException($this->errors);
        }

        return $this->validatedData;
    }

    public function validated(): array
    {
        return $this->validatedData;
    }

    protected function applyRule(array $data, string $key, string $rule)
    {
        $params = explode(':', $rule);
        $method = 'validate' . ucfirst(array_shift($params));

        if (method_exists($this, $method)) {
            $valid = call_user_func_array([$this, $method], [$data, $key, ...$params]);

            if (!$valid) {
                $this->errors[$key][] = $this->getErrorMessage($key, $rule);
            }
        }
    }

    protected function getErrorMessage(string $key, string $rule): string
    {
        $messages = [
            'required' => "The $key field is required.",
            'email' => "The $key must be a valid email address.",
            'min' => "The $key must be at least " . ($rule[1] ?? '') . " characters.",
            'unique' => "The $key has already been taken.",
            'confirmed' => "The $key confirmation does not match.",
        ];

        // Custom error messages can be added here for additional rules as needed

        return $messages[$rule] ?? "The $key field validation failed.";
    }

    protected function validateRequired(array $data, string $key): bool
    {
        return isset($data[$key]) && !empty($data[$key]);
    }

    protected function validateEmail(array $data, string $key): bool
    {
        return isset($data[$key]) && filter_var($data[$key], FILTER_VALIDATE_EMAIL);
    }

    protected function validateMin(array $data, string $key, int $min): bool
    {
        return isset($data[$key]) && strlen($data[$key]) >= $min;
    }

    protected function validateUnique(array $data, string $key, string $table): bool
    {
        // Example assuming User model for uniqueness validation
        $userModel = new User();
        $result = $userModel->where($key, $data[$key]);

        return !$result;
    }

    protected function validateConfirmed(array $data, string $key, string $confirmedKey): bool
    {
        return isset($data[$key]) && isset($data[$confirmedKey]) && $data[$key] === $data[$confirmedKey];
    }
}
