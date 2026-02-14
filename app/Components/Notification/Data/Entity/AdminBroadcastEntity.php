<?php

namespace App\Components\Notification\Data\Entity;

use App\Components\Notification\Data\Enums\BroadcastStatusEnum;
use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminBroadcastEntity extends Model
{
    use HasUuidTrait;

    public $incrementing = false;

    protected $table = 'admin_broadcasts';

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    protected $fillable = [
        'admin_uuid',
        'title',
        'body',
        'data',
        'target_type',
        'target_user_uuids',
        'status',
        'scheduled_at',
        'sent_at',
        'total_recipients',
        'successful_sends',
        'failed_sends',
    ];

    protected $casts = [
        'data' => 'array',
        'target_user_uuids' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => BroadcastStatusEnum::class,
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_uuid', 'uuid');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', BroadcastStatusEnum::DRAFT->value);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', BroadcastStatusEnum::SCHEDULED->value);
    }

    public function scopeSent($query)
    {
        return $query->where('status', BroadcastStatusEnum::SENT->value);
    }

    public function scopePendingScheduled($query)
    {
        return $query->where('status', BroadcastStatusEnum::SCHEDULED->value)
            ->where('scheduled_at', '<=', now());
    }

    public function markAsSent(): void
    {
        $this->update([
            'status' => BroadcastStatusEnum::SENT,
            'sent_at' => now(),
        ]);
    }

    public function markAsFailed(): void
    {
        $this->update([
            'status' => BroadcastStatusEnum::FAILED,
        ]);
    }

    public function incrementSuccessfulSends(int $count = 1): void
    {
        $this->increment('successful_sends', $count);
    }

    public function incrementFailedSends(int $count = 1): void
    {
        $this->increment('failed_sends', $count);
    }
}
