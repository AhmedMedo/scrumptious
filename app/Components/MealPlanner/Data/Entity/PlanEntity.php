<?php

namespace App\Components\MealPlanner\Data\Entity;

use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlanEntity extends Model
{
    use HasFactory;
    use HasUuidTrait;

    public $incrementing = false;

    /** @var string */
    protected $table = 'plans';

    /** @var string */
    protected $primaryKey = 'uuid';

    /** @var string */
    protected $keyType = 'string';

    protected $guarded = [];

    public $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function meals(): HasMany
    {
        return $this->hasMany(MealEntity::class, 'plan_uuid', 'uuid');
    }

}
