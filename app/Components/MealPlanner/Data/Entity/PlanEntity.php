<?php

namespace App\Components\MealPlanner\Data\Entity;

use App\Components\Auth\Data\Entity\UserEntity;
use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use App\Models\Admin;
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

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(UserEntity::class, 'user_uuid', 'uuid');
    }

    public function admin(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_uuid', 'uuid');
    }


}
