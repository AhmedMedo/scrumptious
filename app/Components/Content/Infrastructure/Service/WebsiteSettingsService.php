<?php

namespace App\Components\Content\Infrastructure\Service;

use App\Components\Content\Application\Service\WebsiteSettingsServiceInterface;
use App\Components\Content\Data\Entity\WebsiteSettingsEntity;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class WebsiteSettingsService implements WebsiteSettingsServiceInterface
{
    /**
     * Generate PDF files for privacy policy and terms in both locales.
     */
    public function generatePDF(WebsiteSettingsEntity $websiteSettingsEntity): void
    {
        foreach ([
            'privacy_and_policy_en' => 'en',
            'privacy_and_policy_ar' => 'ar',
            'terms_and_condition_en' => 'en',
            'terms_and_condition_ar' => 'ar',
        ] as $attribute => $locale) {
            $content = $websiteSettingsEntity->getAttribute($attribute);
            if (!empty($content)) {
                $this->generatePDFByContent($websiteSettingsEntity, $attribute, $locale, $content);
            }
        }
    }

    private function generatePDFByContent(WebsiteSettingsEntity $websiteSettingsEntity, string $attribute, string $locale, string $content): void
    {
        $renderedView = view('pdf.content', [
            'content' => $content,
            'currentLocale' => $locale,
        ]);
        $pdf = PDF::loadHTML($renderedView);
        $basePath = 'files/temp/' . $attribute . '.pdf';
        Storage::disk('public')->put($basePath, $pdf->output());
        $path = Storage::disk('public')->path($basePath);

        $websiteSettingsEntity->getMedia($attribute)->each(fn($media) => $media->delete());

        $websiteSettingsEntity->addMedia($path)->toMediaCollection($attribute);
    }
}
