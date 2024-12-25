<?php

namespace App\Components\Content\Application\Query;

use App\Components\Content\Data\Entity\WebsiteSettingsEntity;

interface WebsiteSettingsQueryInterface
{
    public function first(): ?WebsiteSettingsEntity;
}
