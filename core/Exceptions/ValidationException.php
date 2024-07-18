<?php

namespace Core\Exceptions;

use Exception;

class ValidationException extends Exception
{
    protected array $errors;

    /**
     * ValidationException constructor.
     *
     * @param array $errors
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;

        parent::__construct('Validation failed', 422);
    }

    /**
     * Get the validation errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
