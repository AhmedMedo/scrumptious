<?php

namespace App\Components\Auth\Data\Enums;

use App\Libraries\Base\Enum\StandardEnum;

enum UserVerificationTypeEnum: string
{
    use StandardEnum;

    case LOGIN_OTP = 'login_otp';
    case REGISTERATION_OTP = 'registeration_otp';
    case FORGOT_PASSWORD_OTP = 'forgot_password_otp';
    case EMAIL_VERIFICATION = 'email_verification';
    case CHANGE_PHONE = 'change_phone';
}
