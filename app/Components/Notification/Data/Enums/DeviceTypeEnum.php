<?php

namespace App\Components\Notification\Data\Enums;

enum DeviceTypeEnum: string
{
    case IOS = 'ios';
    case ANDROID = 'android';
    case WEB = 'web';

    public function label(): string
    {
        return match($this) {
            self::IOS => 'iOS',
            self::ANDROID => 'Android',
            self::WEB => 'Web',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
