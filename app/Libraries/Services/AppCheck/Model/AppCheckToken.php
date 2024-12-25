<?php

declare(strict_types=1);

namespace App\Libraries\Services\AppCheck\Model;

class AppCheckToken
{
    public function __construct(private readonly array $destinedProjects, private readonly string $issuedByUrl)
    {
    }

    public function isDestinedForProject(string $projectSlug): bool
    {
        return in_array($projectSlug, $this->destinedProjects, true);
    }

    public function hasIssuer(string $issuer): bool
    {
        return $this->issuedByUrl === $issuer;
    }
}
