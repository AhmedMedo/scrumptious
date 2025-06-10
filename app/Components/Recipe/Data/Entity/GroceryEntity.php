<?php

namespace App\Components\Recipe\Data\Entity;

use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use App\ModelFilters\GroceryEntityFilter;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Components\Recipe\Data\Entity\GroceryCategoryEntity;
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
        return $this->provideFilter(GroceryEntityFilter::class);
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(GroceryCategoryEntity::class, 'category_uuid', 'uuid');
    }

}
