<?php

namespace App\Components\Recipe\Data\Entity;

use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use App\ModelFilters\GroceryCategoryEntityFilter;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class GroceryCategoryEntity extends Model implements HasMedia
{
    use HasFactory;
    use HasUuidTrait;
    use InteractsWithMedia;
    use Filterable;

    public $incrementing = false;

    protected $table = 'grocery_category';

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    protected $guarded = [];

    public function modelFilter(): ?string
    {
        return $this->provideFilter(GroceryCategoryEntityFilter::class);
    }

    public function groceries(): HasMany
    {
        return $this->hasMany(GroceryEntity::class, 'category_uuid', 'uuid');
    }
}
