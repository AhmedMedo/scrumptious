<?php

namespace App\Libraries\Messaging\Infrastructure\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $aggregate_uuid
 * @property string $aggregate_type
 * @property string $aggregate
 * @property int $last_version
 */
class SnapshotEntity extends Model
{
    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $table = 'snapshot_storage';

    /** @var array */
    protected $guarded = [];
}
