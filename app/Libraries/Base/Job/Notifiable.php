<?php

namespace App\Libraries\Base\Job;

use App\Component\Content\DomainModel\Country\Enum\CountryEnum;
use App\Libraries\Base\Channel\Mail\MailChannel;
use App\Libraries\Base\Channel\Twilio\TwilioChannel;
use App\Libraries\Base\Channel\Unifonic\UnifonicChannel;
use App\Libraries\Phone\PhoneFormatter;

trait Notifiable
{
    use \Illuminate\Notifications\Notifiable;

    abstract public function getPhoneForNotification(): ?string;

    abstract public function getMailForNotification(): ?string;

    /**
     * @return string[]
     */
    final public function supportedChannels(): array
    {
        $phone = (string) $this->getPhoneForNotification();
        $mail = (string) $this->getMailForNotification();
        $channels = [];

        if ($this->canReceiveSmsMessage($phone)) {
            $shouldUseUnifonic = PhoneFormatter::isPhoneFromCountries($phone, [
                CountryEnum::SAUDI_ARABIA(),
                CountryEnum::UNITED_STATES(),
            ]);

            $channels[] = $shouldUseUnifonic ? UnifonicChannel::class : TwilioChannel::class;
        }

        if ($this->validMail($mail)) {
            $channels[] = MailChannel::class;
        }

        return array_values($channels);
    }

    final public function routeNotificationForNexmo(): string
    {
        return $this->getPhoneForNotification();
    }

    final public function routeNotificationForTwilio(): string
    {
        return $this->getPhoneForNotification();
    }

    final public function routeNotificationForSasms(): string
    {
        return $this->getPhoneForNotification();
    }

    final public function routeNotificationForUnifonic(): string
    {
        return $this->getPhoneForNotification();
    }

    final public function routeNotificationForSMS(): string
    {
        return $this->getPhoneForNotification();
    }

    final public function routeNotificationForMail(): ?string
    {
        return $this->getMailForNotification();
    }

    private function validMail(string $email): bool
    {
        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function canReceiveAlphanumericSender(): bool
    {
        return PhoneFormatter::isPhoneFromCountries((string) $this->getPhoneForNotification(), [
            CountryEnum::GREAT_BRITAIN(),
        ]);
    }

    private function canReceiveSmsMessage(string $phoneNumber): bool
    {
        $notificationRecipients = config('telgani.sms_notification_recipients');

        if ($phoneNumber === '') {
            return false;
        }

        return in_array('*', $notificationRecipients, true) || in_array($phoneNumber, $notificationRecipients, true);
    }
}
