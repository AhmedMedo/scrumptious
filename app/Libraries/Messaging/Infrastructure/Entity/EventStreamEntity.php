<?php

namespace App\Libraries\Messaging\Infrastructure\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @deprecated
 *
 * @property string $event_id
 * @property string $event_name
 * @property int $version
 * @property string $payload
 * @property string $metadata
 * @property string $user_uuid
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 */
class EventStreamEntity extends Model
{
    /** @var string */
    protected $table = 'event_storage';

    /** @var array */
    protected $guarded = [];

    /** @var string[] */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
