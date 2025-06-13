<?php

namespace App\Components\Content\Infrastructure\Service;

use App\Components\Content\Application\Service\WebsiteSettingsServiceInterface;
use App\Components\Content\Data\Entity\WebsiteSettingsEntity;
use Barryvdh\DomPDF\Facade\Pdf;

class WebsiteSettingsService implements WebsiteSettingsServiceInterface
{
    /**
     * Generate PDF files for all translations of the given setting.
     */
    public function generatePDF(WebsiteSettingsEntity $websiteSettingsEntity): void
    {
        foreach (['en', 'ar'] as $locale) {
            $content = $websiteSettingsEntity->getTranslation('value', $locale, false);
            if (!empty($content)) {
                $this->generatePDFByContent($websiteSettingsEntity, $locale, $content);
            }
        }
    }

    private function generatePDFByContent(WebsiteSettingsEntity $websiteSettingsEntity, string $locale, string $content): void
    {
        $attribute = $websiteSettingsEntity->key . '_' . $locale;
        $renderedView = view('pdf.content', [
            'content' => $content,
            'currentLocale' => $locale,
        ]);
        $renderedView = $locale === 'ar' ? $renderedView->toArabicHTML() : $renderedView;
        $pdf = PDF::loadHTML($renderedView);
        $basePath = 'files/temp/' . $attribute . '.pdf';
        $path = storage_path('app/public/' . $basePath);
        $pdf->save($path);

        $websiteSettingsEntity->getMedia($attribute)->each(fn($media) => $media->delete());

        $websiteSettingsEntity->addMedia($path)->toMediaCollection($attribute);
    }
}
