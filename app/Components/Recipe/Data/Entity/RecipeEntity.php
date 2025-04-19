<?php

namespace App\Components\Recipe\Data\Entity;

use App\Components\Auth\Traits\Favoriteable;
use App\Components\Content\Data\Entity\CategoryEntity;
use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use App\ModelFilters\RecipeEntityFilter;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class RecipeEntity extends Model implements HasMedia
{
    use HasFactory;
    use HasUuidTrait;
    use InteractsWithMedia;
    use SoftDeletes;
    use Favoriteable;
    use Filterable;

    public function modelFilter(): ?string
    {
        return $this->provideFilter(RecipeEntityFilter::class);
    }


    /** @var bool */
    public $incrementing = false;

    /** @var string */
    protected $table = 'recipes';

    /** @var string */
    protected $primaryKey = 'uuid';

    /** @var string */
    protected $keyType = 'string';

    protected $guarded = [];

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(IngredientEntity::class, 'recipe_ingredient', 'recipe_uuid', 'ingredient_uuid');
    }

    public function instructions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InstructionEntity::class, 'recipe_uuid', 'uuid');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(CategoryEntity::class, 'category_recipe', 'recipe_uuid', 'category_uuid');
    }
}
