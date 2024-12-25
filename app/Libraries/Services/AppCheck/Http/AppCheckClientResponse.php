<?php

declare(strict_types=1);

namespace App\Libraries\Services\AppCheck\Http;

class AppCheckClientResponse
{
    public function __construct(private readonly int $code, private readonly array $body)
    {
    }

    public function code(): int
    {
        return $this->code;
    }

    public function body(): array
    {
        return $this->body;
    }
}
