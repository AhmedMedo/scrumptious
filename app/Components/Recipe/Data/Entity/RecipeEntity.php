<?php

namespace App\Components\Recipe\Data\Entity;

use App\Components\Content\Data\Entity\CategoryEntity;
use App\Components\Recipe\InstructionEntity;
use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
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

    /** @var bool */
    public $incrementing = false;

    /** @var string */
    protected $table = 'recipes';

    /** @var string */
    protected $primaryKey = 'uuid';

    /** @var string */
    protected $keyType = 'string';

    protected $guarded = [];

    public function ingredients(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RecipeIngredientEntity::class, 'recipe_uuid', 'uuid');
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
