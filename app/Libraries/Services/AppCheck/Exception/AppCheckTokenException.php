<?php

declare(strict_types=1);

namespace App\Libraries\Services\AppCheck\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AppCheckTokenException extends HttpException
{
    public static function invalid(string $reason = ''): self
    {
        $message = 'Invalid AppCheck token.';

        if ($reason !== '') {
            $message = "AppCheck error: $reason";
        }

        return new self(Response::HTTP_BAD_REQUEST, $message);
    }
}
