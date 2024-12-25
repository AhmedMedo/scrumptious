<?php

namespace App\Libraries\Base\Job;

interface RedisNotificationJob
{
    /**
     * @return array
     */
    public function tags(): array;

    /**
     * @return mixed
     */
    public function handle();
}
