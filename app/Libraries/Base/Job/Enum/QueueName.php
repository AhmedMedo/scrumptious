<?php

namespace App\Libraries\Base\Job\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static QueueName OTP()
 * @method static QueueName SMS()
 * @method static QueueName MAIL()
 * @method static QueueName PUSH()
 * @method static QueueName DEFAULT()
 * @method static QueueName TWILIO_OTP()
 * @method static QueueName UNIFONIC_OTP()
 * @method static QueueName LICENSE_SCAN()
 */
class QueueName extends Enum
{
    protected const OTP = 'otp';
    protected const SMS = 'sms';
    protected const MAIL = 'mail';
    protected const PUSH = 'push';
    protected const DEFAULT = 'default';
    protected const TWILIO_OTP = 'twilio_otp';
    protected const UNIFONIC_OTP = 'unifonic_otp';
    protected const LICENSE_SCAN = 'license_scan';
}
