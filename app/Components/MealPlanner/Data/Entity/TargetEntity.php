<?php

namespace App\Components\MealPlanner\Data\Entity;

use App\Components\Auth\Data\Entity\UserEntity;
use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TargetEntity extends Model implements HasMedia
{
    use HasFactory;
    use HasUuidTrait;
    use InteractsWithMedia;

    /** @var bool */
    public $incrementing = false;

    /** @var string */
    protected $table = 'target';

    /** @var string */
    protected $primaryKey = 'uuid';

    /** @var string */
    protected $keyType = 'string';

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserEntity::class, 'user_uuid', 'uuid');
    }
}
