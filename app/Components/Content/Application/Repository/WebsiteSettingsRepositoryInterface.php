<?php

namespace App\Components\Content\Application\Repository;

use Illuminate\Http\UploadedFile;

interface WebsiteSettingsRepositoryInterface
{
    public function update(array $data, ?UploadedFile $bannerImage): void;
}
