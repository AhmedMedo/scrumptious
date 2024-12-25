<?php

namespace App\Components\Auth\Domain\Enum;

use App\Libraries\Base\Enum\StandardEnum;

enum UserStatusEnum: string
{
    use StandardEnum;

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case SUSPENDED = 'suspended';

}
