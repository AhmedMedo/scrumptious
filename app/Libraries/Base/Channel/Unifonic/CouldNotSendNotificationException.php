<?php

namespace App\Libraries\Base\Channel\Unifonic;

use Exception;

class CouldNotSendNotificationException extends Exception
{
    /**
     * @return static
     */
    public static function invalidReceiver(): self
    {
        // @phpstan-ignore-next-line
        return new static(
            'The notifiable did not have a receiving phone number. Add a routeNotificationForUnifonic
            method or a phone_number attribute to your notifiable.'
        );
    }

    public static function errorCodeReceived(string $code, string $message, string $number): self
    {
        return new self(
            'Error code: ' . $code . ' (' . $message . ') received when sending sms to: ' . $number
        );
    }
}
