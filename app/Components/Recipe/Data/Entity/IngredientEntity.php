<?php

namespace App\Components\Recipe\Data\Entity;

use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use App\ModelFilters\IngredientEntityFilter;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class IngredientEntity extends Model
{
    use HasFactory;
    use HasUuidTrait;
    use Filterable;


    /** @var bool */
    public $incrementing = false;

    /** @var string */
    protected $table = 'ingredients';

    /** @var string */
    protected $primaryKey = 'uuid';

    /** @var string */
    protected $keyType = 'string';

    protected $guarded = [];

    public function modelFilter(): ?string
    {
        return $this->provideFilter(IngredientEntityFilter::class);
    }
    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(RecipeEntity::class, 'recipe_ingredient', 'ingredient_uuid', 'recipe_uuid');
    }

}
