<?php

namespace App\Libraries\Messaging\Infrastructure\Entity;

use App\Libraries\Base\Model\HasUuid\HasUuid;
use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * @property string $uuid
 * @property string $aggregate_id
 * @property string $saga_type
 * @property bool $is_done
 * @property string $scenario_name
 * @property array $payload
 * @property Carbon $recorded_on
 */
class StateEntity extends Eloquent implements HasUuid
{
    use HasUuidTrait;

    /** @var bool */
    public $timestamps = false;

    /** @var bool */
    public $incrementing = false;

    /** @var string */
    protected $table = 'state_storage';

    /** @var string */
    protected $primaryKey = 'uuid';

    /** @var string */
    protected $keyType = 'string';

    /** @var array */
    protected $guarded = [];

    /** @var string[] */
    protected $casts = [
        'is_done'     => 'boolean',
        'payload'     => 'array',
        'recorded_on' => 'date',
    ];
}
