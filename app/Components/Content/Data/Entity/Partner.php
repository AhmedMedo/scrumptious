<?php

namespace App\Components\Content\Data\Entity;

use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class Partner extends Model
{
    use HasUuidTrait;
    use InteractsWithMedia;

    /** @var bool */
    public $incrementing = false;

    /** @var string */
    protected $table = 'partners';

    /** @var string */
    protected $primaryKey = 'uuid';

    /** @var string */
    protected $keyType = 'string';

    protected $guarded = [];
}
