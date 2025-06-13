<?php

namespace App\Components\Content\Application\Service;

use App\Components\Content\Data\Entity\WebsiteSettingsEntity;

interface WebsiteSettingsServiceInterface
{
    public function generatePDF(WebsiteSettingsEntity $websiteSettingsEntity): void;
}
