<?php

namespace App\Libraries\Base\Job;

use App\Component\Account\Application\User\Query\V1\Model\UserNotifiable;
use App\Component\Account\Infrastructure\Entity\User\UserEntity;
use App\Libraries\Base\Channel\ChannelMapper;
use Illuminate\Notifications\Notification as BaseNotification;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use ReflectionClass;

abstract class Notification extends BaseNotification
{
    abstract public function getQueue(): Enum\QueueName;

    public function decideIfShouldBeSent(): bool
    {
        return true;
    }

    public static function prepare(): self
    {
        // @phpstan-ignore-next-line
        return new static(...func_get_args());
    }

    public function getPrefixedMessage(string $message): string
    {
        if (App::isProduction()) {
            return $message;
        }

        $env = Str::upper(App::environment());

        return "[$env] $message";
    }

    /**
     * @param UserEntity|UserNotifiable|Notifiable $notifiable
     * @return mixed[]
     */
    public function via($notifiable): array
    {
        return array_intersect($this->supportedChannels(), $notifiable->supportedChannels());
    }

    /**
     * @return string[]
     */
    private function supportedChannels(): array
    {
        $channels = [];
        $reflection = new ReflectionClass($this);

        foreach (ChannelMapper::notificationToChannelMap() as $interface => $channel) {
            if ($reflection->implementsInterface($interface) === true) {
                $channels[] = $channel;
            }
        }

        return $channels;
    }
}
