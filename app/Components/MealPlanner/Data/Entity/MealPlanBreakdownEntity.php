<?php

namespace App\Components\MealPlanner\Data\Entity;

use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MealPlanBreakdownEntity extends Model
{
    use HasFactory;
    use HasUuidTrait;

    public $incrementing = false;

    /** @var string */
    protected $table = 'meal_plan_breakdowns';

    /** @var string */
    protected $primaryKey = 'uuid';

    /** @var string */
    protected $keyType = 'string';

    protected $guarded = [];

    public $casts = [
        'date' => 'date',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(PlanEntity::class, 'plan_uuid', 'uuid');
    }

    public function meals(): HasMany
    {
        return $this->hasMany(MealEntity::class, 'breakdown_uuid', 'uuid');
    }
}
