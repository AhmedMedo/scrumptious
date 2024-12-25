<?php

namespace App\Components\Auth\Data\Entity;

use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use Illuminate\Database\Eloquent\Model;

class UserOldPasswordEntity extends Model
{
    use HasUuidTrait;

    /** @var bool */
    public $incrementing = false;

    /** @var string */
    protected $table = 'user_old_passwords';

    /** @var string */
    protected $primaryKey = 'uuid';

    /** @var string */
    protected $keyType = 'string';

    protected $guarded = [];
}
