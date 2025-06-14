<?php

namespace App\Components\Content\Data\Entity;

use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class WebsiteSettingsEntity extends Model implements HasMedia
{
    use HasUuidTrait;
    use InteractsWithMedia;

    /** @var bool */
    public $incrementing = false;

    /** @var string */
    protected $table = 'website_settings';

    /** @var string */
    protected $primaryKey = 'uuid';

    /** @var string */
    protected $keyType = 'string';


    protected $guarded = [];
}
