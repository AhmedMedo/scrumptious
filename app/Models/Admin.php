<?php

namespace App\Models;

use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Admin  extends Authenticatable
{
    use HasUuidTrait;
    use HasRoles;


    /** @var bool */
    public $incrementing = false;

    /** @var string */
    protected $table = 'admins';

    /** @var string */
    protected $primaryKey = 'uuid';

    /** @var string */
    protected $keyType = 'string';


    protected string $guard_name = 'admin';

    public function getNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
