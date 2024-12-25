<?php

namespace App\Libraries\Base\Job;

use App\Component\Account\Application\User\Query\V1\Model\UserNotifiable;
use App\Component\Account\Application\User\Query\V1\Model\UserNotifiableMail;
use App\Component\Account\Infrastructure\Entity\User\UserEntity;
use App\Libraries\Base\Channel\Twilio\TwilioChannel;
use App\Libraries\Base\Channel\Unifonic\UnifonicChannel;
use App\Libraries\Base\Job\Enum\QueueName;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class NotificationJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected mixed $source;

    public function __construct(
        protected Notifiable|UserNotifiable|UserNotifiableMail|UserEntity $notifiable,
        protected Notification $notification
    ) {
        $this->source = config('telgani.ms_name');
        $this->connection = config('queue.connections.redis.driver');
        $this->queue = $this->queueName();
    }

    public function notifiable(): Notifiable|UserNotifiable|UserNotifiableMail|UserEntity
    {
        return $this->notifiable;
    }

    private function queueName(): string
    {
        $channels = $this->notifiable->supportedChannels();
        $isOtpNotification = $this->notification->getQueue()->equals(QueueName::OTP());

        return match (true) {
            $isOtpNotification
            && in_array(TwilioChannel::class, $channels, true) => $this->convertQueueName(
                QueueName::TWILIO_OTP()
            ),
            $isOtpNotification
            && in_array(UnifonicChannel::class, $channels, true) => $this->convertQueueName(
                QueueName::UNIFONIC_OTP()
            ),
            default => $this->convertQueueName($this->notification->getQueue()),
        };
    }

    private function convertQueueName(QueueName $queueName): string
    {
        return sprintf('%s_%s', app()->environment(), $queueName->getValue());
    }
}
