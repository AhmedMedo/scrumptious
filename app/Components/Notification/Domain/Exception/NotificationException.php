<?php

namespace App\Components\Notification\Domain\Exception;

use Exception;

class NotificationException extends Exception
{
    public static function notificationNotFound(string $uuid): self
    {
        return new self("Notification with UUID {$uuid} not found.");
    }

    public static function userNotFound(string $userUuid): self
    {
        return new self("User with UUID {$userUuid} not found.");
    }

    public static function failedToSendNotification(string $reason): self
    {
        return new self("Failed to send notification: {$reason}");
    }

    public static function invalidNotificationType(string $type): self
    {
        return new self("Invalid notification type: {$type}");
    }

    public static function noDeviceTokensFound(string $userUuid): self
    {
        return new self("No active device tokens found for user {$userUuid}");
    }
}
