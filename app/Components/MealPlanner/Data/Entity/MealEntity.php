<?php

namespace App\Components\MealPlanner\Data\Entity;

use App\Components\Recipe\Data\Entity\RecipeEntity;
use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MealEntity extends Model
{
    use HasFactory;
    use HasUuidTrait;

    public $incrementing = false;

    /** @var string */
    protected $table = 'meals';

    /** @var string */
    protected $primaryKey = 'uuid';

    /** @var string */
    protected $keyType = 'string';

    protected $guarded = [];

    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(RecipeEntity::class, 'meal_recipe', 'meal_uuid', 'recipe_uuid');
    }
}
