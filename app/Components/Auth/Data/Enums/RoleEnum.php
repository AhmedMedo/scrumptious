<?php

namespace App\Components\Auth\Data\Enums;

use App\Libraries\Base\Enum\StandardEnum;

enum RoleEnum: string
{
    use StandardEnum;

    case SUPER_ADMIN = 'Super Admin';
    case ADMIN = 'Admin';
    case USER = 'User';
    case GUEST = 'Guest';
}
