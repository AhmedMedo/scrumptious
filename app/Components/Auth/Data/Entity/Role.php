<?php

namespace App\Components\Auth\Data\Entity;

use App\Libraries\Base\Model\HasUuid\HasUuidTrait;

class Role extends \Spatie\Permission\Models\Role
{
    use HasUuidTrait;

    /** @var bool */
    public $incrementing = false;

    /** @var string */
    protected $table = 'roles';

    /** @var string */
    protected $primaryKey = 'uuid';

    /** @var string */
    protected $keyType = 'string';
}
