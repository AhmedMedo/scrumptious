<?php

namespace App\Libraries\Messaging\Aggregate;

use Illuminate\Validation\ValidationException;
use RuntimeException;
use Throwable;

abstract class AggregateException extends RuntimeException
{
    /** @var string[] */
    protected array $errors = [];

    /**
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, intval($code) === 0 ? 403 : $code, $previous);
    }

    public static function fromValidation(string $message, ValidationException $exception): AggregateException
    {
        // @phpstan-ignore-next-line
        $e = new static($message, 403, $exception);
        $e->errors = $exception->errors();

        return $e;
    }

    public static function initialize(Throwable $exception): AggregateException
    {
        // @phpstan-ignore-next-line
        return new static($exception->getMessage(), 403, $exception);
    }

    /**
     * @return string[]
     */
    public function errors(): array
    {
        return $this->errors;
    }

    public function httpCode(int $defaultStatusCode = 200): int
    {
        $statusCode = $this->getCode();

        if ($statusCode < 100 || $statusCode >= 600) {
            return $defaultStatusCode;
        }

        return $statusCode;
    }
}
