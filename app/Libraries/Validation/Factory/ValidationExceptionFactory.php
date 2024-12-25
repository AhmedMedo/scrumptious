<?php

namespace App\Libraries\Validation\Factory;

use Exception;
use Illuminate\Validation\ValidationException;

class ValidationExceptionFactory
{
    public function withMessagesFromException(string $field, Exception $exception): ValidationException
    {
        return ValidationException::withMessages([
            $field => [$exception->getMessage()],
        ]);
    }
}
