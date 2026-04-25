<?php

namespace App\Models;

use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable implements FilamentUser
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

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
