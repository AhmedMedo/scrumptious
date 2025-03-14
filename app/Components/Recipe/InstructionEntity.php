<?php

namespace App\Components\Recipe;

use App\Components\Recipe\Data\Entity\RecipeEntity;
use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructionEntity extends Model
{
    use HasFactory;
    use HasUuidTrait;

    /** @var bool */
    public $incrementing = false;

    /** @var string */
    protected $table = 'instructions';

    /** @var string */
    protected $primaryKey = 'uuid';

    /** @var string */
    protected $keyType = 'string';

    protected $guarded = [];

    public function recipe(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RecipeEntity::class, 'recipe_uuid', 'uuid');
    }
}
