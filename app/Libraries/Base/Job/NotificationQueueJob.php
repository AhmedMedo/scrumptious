<?php

namespace App\Libraries\Base\Job;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Redis;

class NotificationQueueJob extends NotificationJob implements RedisNotificationJob
{
    /**
     * @inheritDoc
     */
    public function tags(): array
    {
        return [$this->queue, $this->source];
    }

    /**
     * @inheritDoc
     */
    public function handle(): void
    {
        /** @var Notifiable $notifiable */
        [$key, $notifiable] = [$this->queue, $this->notifiable];

        Redis::funnel($key)->limit(1)->then(function () use ($notifiable, $key): void {
            Redis::throttle($key)->allow(5)->every(2)->block(2)->then(function () use ($notifiable): void {
                if ($this->notification->decideIfShouldBeSent()) {
                    $notifiable->notify($this->notification);
                }
            }, fn () => // Could not obtain lock...
            $this->release(4));
        }, fn () => // Could not obtain lock; this job will be re-queued
        $this->release(25));
    }
}
