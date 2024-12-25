<?php

namespace App\Libraries\Messaging\Query\Exception;

use RuntimeException;

class ResourceNotFoundException extends RuntimeException
{
    public function httpCode(int $defaultStatusCode = 200): int
    {
        $statusCode = $this->getCode();

        if ($statusCode < 100 || $statusCode >= 600) {
            return $defaultStatusCode;
        }

        return $statusCode;
    }
}
