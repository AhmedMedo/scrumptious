<?php

namespace App\Components\Content\Infrastructure\Repository;

use App\Components\Content\Application\Query\WebsiteSettingsQueryInterface;
use App\Components\Content\Application\Repository\WebsiteSettingsRepositoryInterface;
use App\Components\Content\Application\Service\WebsiteSettingsServiceInterface;
use App\Components\Content\Data\Entity\WebsiteSettingsEntity;
use Illuminate\Http\UploadedFile;

class WebsiteSettingsRepository implements WebsiteSettingsRepositoryInterface
{
    public function __construct(
        private readonly WebsiteSettingsServiceInterface $websiteSettingsService
    ) {
    }

    public function update(array $data, ?UploadedFile $bannerImage): void
    {
        $websiteSettings = $this->websiteSettingsQuery->first();
        if ($websiteSettings === null) {
            $websiteSettings = new WebsiteSettingsEntity();
        }

        $websiteSettings->fill($data);
        $websiteSettings->save();

        $this->websiteSettingsService->generatePDF($websiteSettings);
    }
}
