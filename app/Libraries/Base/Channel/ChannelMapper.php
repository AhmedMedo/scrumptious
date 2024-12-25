<?php

namespace App\Libraries\Base\Channel;

class ChannelMapper
{
    /**
     * @return string[]
     */
    public static function notificationToChannelMap(): array
    {
        $map = [
            Mail\MailNotification::class         => Mail\MailChannel::class,
            Twilio\TwilioNotification::class     => Twilio\TwilioChannel::class,
            Unifonic\UnifonicNotification::class => Unifonic\UnifonicChannel::class,
        ];

        if (config('telgani.NOTIFICATIONS_DISABLED') === true) {
            return [];
        }

        if (config('telgani.NOTIFICATIONS_SMS_DISABLED') === true) {
            unset($map[Twilio\TwilioNotification::class], $map[Unifonic\UnifonicNotification::class]);
        }

        if (config('telgani.NOTIFICATIONS_EMAIL_DISABLED') === true) {
            unset($map[Mail\MailNotification::class]);
        }

        return $map;
    }
}
