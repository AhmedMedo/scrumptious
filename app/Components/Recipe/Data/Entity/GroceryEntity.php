<?php

namespace App\Components\Recipe\Data\Entity;

use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use App\ModelFilters\IngredientEntityFilter;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class GroceryEntity extends Model implements HasMedia
{
    use HasFactory;
    use HasUuidTrait;
    use Filterable;
    use InteractsWithMedia;


    /** @var bool */
    public $incrementing = false;

    /** @var string */
    protected $table = 'groceries';

    /** @var string */
    protected $primaryKey = 'uuid';

    /** @var string */
    protected $keyType = 'string';

    protected $guarded = [];

    public function modelFilter(): ?string
    {
        return $this->provideFilter(IngredientEntityFilter::class);
    }

}
