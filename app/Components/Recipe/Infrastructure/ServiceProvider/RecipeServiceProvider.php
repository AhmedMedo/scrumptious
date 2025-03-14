<?php

namespace App\Components\Recipe\Infrastructure\ServiceProvider;

use App\Components\Content\Application\Query\WebsiteSettingsQueryInterface;
use App\Components\Content\Application\Repository\CountryRepositoryInterface;
use App\Components\Content\Application\Repository\WebsiteSettingsRepositoryInterface;
use App\Components\Content\Application\Service\WebsiteSettingsServiceInterface;
use App\Components\Content\Infrastructure\Query\WebsiteSettingsQuery;
use App\Components\Content\Infrastructure\Repository\CountryRepository;
use App\Components\Content\Infrastructure\Repository\WebsiteSettingsRepository;
use App\Components\Content\Infrastructure\Service\WebsiteSettingsService;
use App\Libraries\Base\Support\ServiceProvider;

class RecipeServiceProvider extends ServiceProvider
{
    protected function regularBindings(): array
    {
        return [
        ];
    }
}
