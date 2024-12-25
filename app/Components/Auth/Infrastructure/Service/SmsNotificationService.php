<?php

namespace App\Components\Auth\Infrastructure\Service;

use App\Components\Auth\Data\Entity\UserEntity;
use Illuminate\Support\Facades\Log;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class SmsNotificationService
{


    public function __construct(
        private readonly Client $client
    )
    {
    }


    /**
     * @throws TwilioException
     */
    public function sendVerificationMessage(string $phoneNumber, string $verificationCode): ?\Twilio\Rest\Api\V2010\Account\MessageInstance
    {
        if (in_array(app()->environment(), ['local', 'development'])) {
            return null;
        }
        Log::debug("Sending verification message to {$phoneNumber}",[
            'phoneNumber' => $phoneNumber,
            'verificationCode' => $verificationCode,
            'from' => config('giftit.twilio_number')
        ]);
        return $this->client->messages->create('+'.$phoneNumber,[
            'from' => config('giftit.twilio_number'),
            'body' => 'Your verification code is: '.$verificationCode
        ]);
    }
}
