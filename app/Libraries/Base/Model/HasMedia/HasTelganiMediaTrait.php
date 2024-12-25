<?php

declare(strict_types=1);

namespace App\Libraries\Base\Model\HasMedia;

use DateTimeInterface;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasTelganiMediaTrait
{
    public function getTelganiMediaUrl(string $collection): string
    {
        if ($media = $this->getFirstMedia($collection)) {
            if (! $this->checkIfMediaHasPublicACL($media)) {
                return route('content.media.show', [$media->id]);
            }
        }

        return $this->getFirstMediaUrl($collection);
    }

    public function getTelganiMediaId(string $collection): ?int
    {
        $media = $this->getFirstMedia($collection);

        if (! $media) {
            return null;
        }

        return (int) $media->id;
    }

    public function checkIfMediaHasPublicACL(Media $media): bool
    {
        if ($media->hasCustomProperty('custom_headers')) {
            $headers = $media->getCustomHeaders();

            if (key_exists('ACL', $headers) && $headers['ACL'] == 'public-read') {
                return true;
            }
        }

        return false;
    }

    public function setTelganiMedia(
        HasMedia $model,
        UploadedFile $uploadedFile,
        string $collection,
        bool $publicACL = false
    ): HasMedia {
        if ($model->hasMedia($collection)) {
            $this->deleteTelganiMedia($model, $collection);
        }

        $fileName = md5($uploadedFile->hashName() . microtime());

        if ($publicACL) {
            $aclPermission = ['ACL' => 'public-read'];
        } else {
            $aclPermission = [];
        }

        $model->addMedia($uploadedFile)
            ->setName($fileName)
            ->usingFileName($fileName)
            ->addCustomHeaders($aclPermission)
            ->toMediaCollection($collection);

        return $model;
    }

    public function deleteTelganiMedia(HasMedia $model, string $collection): void
    {
        $media = $model->getMedia($collection);
        $model->deleteMedia($media->first());
    }

    public function getTelganiMediaFirstTemporaryUrl(
        DateTimeInterface $expiration,
        string $collectionName = 'default',
        string $conversionName = '',
        array $options = []
    ): string {
        if (config('media-library.default_filesystem') === 's3') {
            return (string) $this->getFirstMedia($collectionName)?->getTemporaryUrl($expiration, $conversionName, $options);
        }

        return $this->getFirstMediaUrl($collectionName);
    }
}
