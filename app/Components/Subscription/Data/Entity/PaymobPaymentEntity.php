<?php

namespace App\Components\Subscription\Data\Entity;

use App\Components\Auth\Data\Entity\UserEntity;
use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymobPaymentEntity extends Model
{
    use HasFactory;
    use HasUuidTrait;

    public $incrementing = false;

    protected $table = 'paymob_payments';

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    protected $guarded = [];

    protected $casts = [
        'response' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserEntity::class, 'user_uuid', 'uuid');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlanEntity::class, 'subscription_plan_uuid', 'uuid');
    }
}
