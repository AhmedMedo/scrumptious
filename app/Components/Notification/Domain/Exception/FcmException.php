<?php

namespace App\Components\Notification\Domain\Exception;

use Exception;

class FcmException extends Exception
{
    public static function invalidToken(string $token): self
    {
        return new self("Invalid FCM token: {$token}");
    }

    public static function sendFailed(string $reason): self
    {
        return new self("Failed to send FCM notification: {$reason}");
    }

    public static function configurationError(string $message): self
    {
        return new self("FCM configuration error: {$message}");
    }

    public static function invalidPayload(string $reason): self
    {
        return new self("Invalid FCM payload: {$reason}");
    }
}
