<?php

namespace App\Components\Content\Infrastructure\Repository;

use App\Components\Content\Application\Query\WebsiteSettingsQueryInterface;
use App\Components\Content\Application\Repository\WebsiteSettingsRepositoryInterface;
use App\Components\Content\Application\Service\WebsiteSettingsServiceInterface;
use App\Components\Content\Data\Entity\WebsiteSettingsEntity;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class WebsiteSettingsRepository implements WebsiteSettingsRepositoryInterface
{
    const CONTENTS_NAMES = [
        'privacy_content_en',
        'privacy_content_ar',
        'terms_content_en',
        'terms_content_ar',
        'about_us_content_en',
        'about_us_content_ar'
    ];

    public function __construct(
        private readonly WebsiteSettingsQueryInterface $websiteSettingsQuery,
        private readonly WebsiteSettingsServiceInterface $websiteSettingsService
    ) {
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function update(array $data, ?UploadedFile $bannerImage): void
    {
        $websiteSettings = $this->websiteSettingsQuery->first();
        if ($websiteSettings === null) {
            $websiteSettings = new WebsiteSettingsEntity();
        }
        $websiteSettings->fill($data);
        $getChangedAttributes = array_filter(array_keys($websiteSettings->getDirty()), fn($item) => in_array($item, self::CONTENTS_NAMES));

        $websiteSettings->save();

        if ($bannerImage !== null) {
            $websiteSettings->addMedia($bannerImage)->toMediaCollection('banner');
        }

        $this->websiteSettingsService->generatePDF($websiteSettings, $getChangedAttributes);
    }
}
