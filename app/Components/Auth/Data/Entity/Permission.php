<?php

namespace App\Components\Auth\Data\Entity;

use App\Libraries\Base\Model\HasUuid\HasUuidTrait;

class Permission extends \Spatie\Permission\Models\Permission
{
    use HasUuidTrait;

    /** @var bool */
    public $incrementing = false;

    /** @var string */
    protected $table = 'permissions';

    /** @var string */
    protected $primaryKey = 'uuid';

    /** @var string */
    protected $keyType = 'string';
}
