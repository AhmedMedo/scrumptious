<?php

namespace App\Libraries\Base\Channel\Unifonic;

use App\Component\Account\Application\User\Query\V1\Model\UserNotifiable;
use App\Component\Account\Infrastructure\Entity\User\UserEntity;
use App\Libraries\Base\Job\Notifiable;

interface UnifonicNotification
{
    /**
     * @param UserEntity|UserNotifiable|Notifiable $notifiable
     *
     * @return string
     */
    public function toUnifonic($notifiable): string;
}
