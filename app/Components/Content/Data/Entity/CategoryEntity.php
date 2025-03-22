<?php

namespace App\Components\Content\Data\Entity;

use App\Components\Recipe\Data\Entity\RecipeEntity;
use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CategoryEntity extends Model implements HasMedia
{
    use HasFactory;
    use HasUuidTrait;
    use InteractsWithMedia;


    /** @var bool */
    public $incrementing = false;

    /** @var string */
    protected $table = 'category';

    /** @var string */
    protected $primaryKey = 'uuid';

    /** @var string */
    protected $keyType = 'string';

    protected $guarded = [];


    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(RecipeEntity::class, 'category_recipe', 'category_uuid', 'recipe_uuid');
    }

}
