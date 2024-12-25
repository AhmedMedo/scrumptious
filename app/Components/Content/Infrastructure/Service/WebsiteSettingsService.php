<?php

namespace App\Components\Content\Infrastructure\Service;

use App\Components\Content\Application\Service\WebsiteSettingsServiceInterface;
use App\Components\Content\Data\Entity\WebsiteSettingsEntity;
use Barryvdh\DomPDF\Facade\Pdf;

class WebsiteSettingsService implements WebsiteSettingsServiceInterface
{
    public function generatePDF(WebsiteSettingsEntity $websiteSettingsEntity, array $changedAttributes): void
    {
        foreach ($changedAttributes as $item) {
            if (!empty($websiteSettingsEntity->getAttribute($item))) {
                $this->generatePDFByContent($websiteSettingsEntity, $item);
            }
        }
    }

    private function generatePDFByContent(WebsiteSettingsEntity $websiteSettingsEntity, string $attribute): void
    {
        $array = explode('_', $attribute);
        $currentLocale = array_pop($array);
        $content = $websiteSettingsEntity->getAttribute($attribute);
        $renderedView = view('pdf.content', compact('content', 'currentLocale'));
        $renderedView = $currentLocale == 'ar' ? $renderedView->toArabicHTML() : $renderedView;
        $pdf = PDF::loadHTML($renderedView);
        $basePath = 'files/temp/' . $attribute . '.pdf';
        $path = storage_path('app/public/' . $basePath);
//        dd($path);
        $pdf->save($path);

        $websiteSettingsEntity->getMedia($attribute)->each(fn($media) => $media->delete());

        $websiteSettingsEntity->addMedia($path)->toMediaCollection($attribute);
    }
}
