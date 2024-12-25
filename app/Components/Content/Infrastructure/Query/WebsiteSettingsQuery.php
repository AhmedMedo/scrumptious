<?php

namespace App\Components\Content\Infrastructure\Query;

use App\Components\Content\Application\Query\WebsiteSettingsQueryInterface;
use App\Components\Content\Data\Entity\WebsiteSettingsEntity;

class WebsiteSettingsQuery implements WebsiteSettingsQueryInterface
{
    public function first(): ?WebsiteSettingsEntity
    {
        return WebsiteSettingsEntity::first();
    }
}
