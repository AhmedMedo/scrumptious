<?php

namespace App\Components\Auth\Data\Entity;

use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use Illuminate\Database\Eloquent\Model;

class NotificationEntity extends Model
{
    use HasUuidTrait;

    /** @var bool */
    public $incrementing = false;

    /** @var string */
    protected $table = 'notifications';

    /** @var string */
    protected $primaryKey = 'uuid';

    /** @var string */
    protected $keyType = 'string';
}
