<?php

namespace App\Libraries\Base\Channel\Mail;

use App\Component\Account\Application\User\Query\V1\Model\UserNotifiable;
use App\Component\Account\Infrastructure\Entity\User\UserEntity;
use App\Libraries\Base\Job\Notifiable;
use Illuminate\Contracts\Mail\Mailable;

interface MailNotification
{
    /**
     * @param UserEntity|UserNotifiable|Notifiable $notifiable
     *
     * @return Mailable
     */
    public function toMail($notifiable): Mailable;
}
