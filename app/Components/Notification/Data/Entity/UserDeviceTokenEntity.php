<?php

namespace App\Components\Notification\Data\Entity;

use App\Components\Auth\Data\Entity\UserEntity;
use App\Components\Notification\Data\Enums\DeviceTypeEnum;
use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDeviceTokenEntity extends Model
{
    use HasUuidTrait;

    public $incrementing = false;

    protected $table = 'user_device_tokens';

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    protected $fillable = [
        'user_uuid',
        'device_token',
        'device_type',
        'device_name',
        'is_active',
        'last_used_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'device_type' => DeviceTypeEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserEntity::class, 'user_uuid', 'uuid');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeByDeviceType($query, DeviceTypeEnum $type)
    {
        return $query->where('device_type', $type->value);
    }

    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    public function updateLastUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }
}
