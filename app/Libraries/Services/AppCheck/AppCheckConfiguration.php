<?php

declare(strict_types=1);

namespace App\Libraries\Services\AppCheck;

use Illuminate\Config\Repository;

class AppCheckConfiguration
{
    public function __construct(private readonly Repository $config)
    {
    }

    public function signatureHeader(): string
    {
        return 'X-Firebase-AppCheck';
    }

    public function platformHeader(): string
    {
        return 'Platform';
    }

    public function appHeader(): string
    {
        return 'App';
    }

    public function isEnabled(): bool
    {
        return (bool) $this->config->get('firebase.app_check.enabled');
    }

    public function cacheTtl(): int
    {
        return (int) $this->config->get('firebase.app_check.keys_cache_in_seconds_ttl');
    }

    public function projectNumber(): string
    {
        return (string) $this->config->get('firebase.project_number');
    }

    public function projectId(): string
    {
        return sprintf('projects/%s', $this->projectNumber());
    }

    public function supportedPlatforms(): array
    {
        return explode(
            separator: ',',
            string: (string) $this->config->get('firebase.app_check.supported_platforms')
        );
    }

    public function supportedApps(): array
    {
        return explode(
            separator: ',',
            string: (string) $this->config->get('firebase.app_check.supported_apps')
        );
    }
}
