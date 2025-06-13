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
        private readonly WebsiteSettingsQueryInterface $websiteSettingsQuery,
        private readonly WebsiteSettingsServiceInterface $websiteSettingsService
    ) {
    }

    public function update(array $data, ?UploadedFile $bannerImage): void
    {
        $websiteSettings = WebsiteSettingsEntity::where('key', $data['key'] ?? null)->first();
        if ($websiteSettings === null) {
            $websiteSettings = new WebsiteSettingsEntity();
            $websiteSettings->key = $data['key'];
        }

        $websiteSettings->setTranslations('value', $data['value'] ?? []);
        $websiteSettings->save();

        $this->websiteSettingsService->generatePDF($websiteSettings);
    }
}
