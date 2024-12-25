<?php

namespace App\Components\Content\Data\Entity;

use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class CountryEntity extends Model
{
    use HasUuidTrait;
    use HasTranslations;

    /** @var bool */
    public $incrementing = false;

    /** @var string */
    protected $table = 'country';

    /** @var string */
    protected $primaryKey = 'uuid';

    /** @var string */
    protected $keyType = 'string';

    /** @var array<int, string> */
    protected $fillable = [
        'name',
        'iso_code',
        'iso3_code',
        'country_code',
        'currency_code',
        'flag',
        'is_global',
    ];

    /** @var array<int, string> */
    public $translatable = [
        'name',
    ];


    public function giftCards(): hasMany
    {
        return $this->hasMany(GiftCardEntity::class, 'country_uuid', 'uuid');
    }
}
