<?php

namespace App\Libraries\Base\Utilities;

use Illuminate\Http\Request;

class ClientApp
{
    public const PLATFORM_IOS = 'ios';
    public const PLATFORM_ANDROID = 'android';

    public static ?Version $version = null;

    public static $language;

    public static $platform;

    public static bool $isDev = false;

    public static function setRequest(Request $request): void
    {
        if ($request->headers->has('version')) {
            self::setVersion($request->headers->get('version'));
        }
        if ($request->headers->has('platform')) {
            self::setPlatform($request->headers->get('platform'));
        }
        if ($request->headers->has('language')) {
            self::setLanguage($request->headers->get('language'));
        }
    }

    /**
     * @param mixed $version
     */
    public static function setVersion($version): void
    {
        self::$version = new Version($version);
    }

    /**
     * @param mixed $platform
     */
    public static function setPlatform($platform): void
    {
        self::$platform = $platform;
    }

    /**
     * @param mixed $language
     */
    public static function setLanguage($language): void
    {
        self::$language = $language;
    }

    public static function versionIsDev(): bool
    {
        return self::$version->isDev();
    }

    public static function hasVersionGreaterThan(?string $minimalRequiredVersion): bool
    {
        if (self::$version === null) {
            self::setRequest(request());
        }

        if (self::$version === null) {
            return true;
        }

        return self::$version->greaterThan(new Version($minimalRequiredVersion));
    }

    public static function hasVersionGreaterThanOrEqualTo(?string $minimalRequiredVersion): bool
    {
        if (self::$version === null) {
            self::setRequest(\request());
        }

        if (self::$version === null) {
            return true;
        }

        return self::$version->greaterThanOrEqualTo(new Version($minimalRequiredVersion));
    }
}
