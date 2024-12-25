<?php

namespace App\Libraries\Valuing\Identity\Uuid;

use App\Libraries\Valuing\Identity\IdentityGenerator;
use Ramsey\Uuid\Uuid;

class UuidGenerator implements IdentityGenerator
{
    /**
     * @inheritDoc
     */
    public function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}
