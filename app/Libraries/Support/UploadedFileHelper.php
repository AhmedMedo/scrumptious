<?php

namespace App\Libraries\Support;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Flysystem\FilesystemException;
use Symfony\Component\HttpFoundation\File\File;

class UploadedFileHelper
{
    /**
     * @throws FilesystemException
     */
    public static function retrieveFile(string $filePath): File
    {
        $tempFilePath = sys_get_temp_dir() . '/' . Str::uuid();
        if (file_put_contents($tempFilePath, Storage::disk('s3')->read($filePath)) !== false) {
            Storage::disk('s3')->delete($filePath);
        }

        return new File($tempFilePath);
    }

    public static function retrieveUploadedFile(?string $filePath, string $fileName = null): ?UploadedFile
    {
        if (!$filePath) {
            return null;
        }

        try {
            $file = self::retrieveFile($filePath);
            $fileName = $fileName ?: Str::afterLast($file->getPathname(), '/');

            return new UploadedFile(
                $file->getPathname(),
                $fileName,
                $file->getMimeType(),
                0,
                true
            );
        } catch (Exception $exception) {
            return null;
        }
    }
}
