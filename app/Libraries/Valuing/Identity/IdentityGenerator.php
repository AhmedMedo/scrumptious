<?php

namespace App\Libraries\Valuing\Identity;

interface IdentityGenerator
{
    public function generate(): string;
}
