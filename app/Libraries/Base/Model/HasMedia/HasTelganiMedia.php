<?php

namespace App\Libraries\Base\Model\HasMedia;

use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

interface HasTelganiMedia
{
    public function getTelganiMediaUrl(string $collection): string;

    public function checkIfMediaHasPublicACL(Media $media): bool;

    public function setTelganiMedia(
        HasMedia $model,
        UploadedFile $uploadedFile,
        string $collection,
        bool $publicACL = false
    ): HasMedia;

    public function deleteTelganiMedia(HasMedia $model, string $collection): void;
}
