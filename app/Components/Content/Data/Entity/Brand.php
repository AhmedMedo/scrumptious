<?php

namespace App\Components\Content\Data\Entity;

use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Brand extends Model implements HasMedia
{
    use HasFactory;
    use HasUuidTrait;
    use HasTranslations;
    use InteractsWithMedia;
    use HasTranslations;


    /** @var bool */
    public $incrementing = false;

    /** @var string */
    protected $table = 'brands';

    /** @var string */
    protected $primaryKey = 'uuid';

    /** @var string */
    protected $keyType = 'string';

    protected $guarded = [];

    /** @var array */
    public array $translatable = ['name'];

}
