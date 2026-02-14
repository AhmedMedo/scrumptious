<?php

namespace App\Components\Notification\Data\Entity;

use App\Components\Auth\Data\Entity\UserEntity;
use App\Components\Notification\Data\Enums\NotificationTypeEnum;
use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationEntity extends Model
{
    use HasUuidTrait;

    public $incrementing = false;

    protected $table = 'notifications';

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    protected $fillable = [
        'user_uuid',
        'type',
        'title',
        'body',
        'data',
        'is_read',
        'read_at',
        'sent_via',
        'fcm_sent_at',
        'email_sent_at',
    ];

    protected $casts = [
        'data' => 'array',
        'sent_via' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'fcm_sent_at' => 'datetime',
        'email_sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'type' => NotificationTypeEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserEntity::class, 'user_uuid', 'uuid');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeByType($query, NotificationTypeEnum $type)
    {
        return $query->where('type', $type->value);
    }

    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
}
