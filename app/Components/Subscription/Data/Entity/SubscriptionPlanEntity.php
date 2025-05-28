<?php

namespace App\Components\Subscription\Data\Entity;

use App\Components\Auth\Data\Entity\UserEntity;
use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlanEntity extends Model
{
    use HasFactory;
    use HasUuidTrait;

    public $incrementing = false;

    /** @var string */
    protected $table = 'subscription_plans';

    /** @var string */
    protected $primaryKey = 'uuid';

    /** @var string */
    protected $keyType = 'string';

    protected $guarded = [];

    public $casts = [
        'is_active' => 'boolean',
    ];


}
