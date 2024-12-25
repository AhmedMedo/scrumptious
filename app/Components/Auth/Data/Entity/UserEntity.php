<?php

namespace App\Components\Auth\Data\Entity;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Components\Content\Data\Entity\CountryEntity;
use App\Components\GiftCards\Data\Entity\CartEntity;
use App\Components\GiftCards\Data\Entity\GiftCardOrderEntity;
use App\Components\GiftCards\Data\Entity\TransactionEntity;
use App\Components\GiftCards\Data\Entity\Wallet\UserWalletEntity;
use App\Components\GiftCards\Domain\Enum\CartStatusEnum;
use App\Libraries\Base\Model\HasUuid\HasUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class UserEntity extends Authenticatable implements HasMedia
{
    use HasApiTokens;
    use HasFactory;
//    use Notifiable;
    use HasUuidTrait;
    use InteractsWithMedia;
    use SoftDeletes;
    use HasRoles;


    /** @var bool */
    public $incrementing = false;

    /** @var string */
    protected $table = 'users';

    /** @var string */
    protected $primaryKey = 'uuid';

    /** @var string */
    protected $keyType = 'string';


    protected string $guard_name = 'api';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function notifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(NotificationEntity::class, 'user_uuid', 'uuid');
    }

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CountryEntity::class, 'country_uuid', 'uuid');
    }


    public function activeCart(): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasOne|CartEntity|null
    {
        return $this->hasOne(CartEntity::class, 'user_uuid', 'uuid')
            ->where('status', CartStatusEnum::CREATED->value)->first();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(GiftCardOrderEntity::class, 'user_uuid', 'uuid');

    }

    public function wallets(): HasMany
    {
        return  $this->hasMany(UserWalletEntity::class,'user_uuid','uuid');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(TransactionEntity::class, 'user_uuid', 'uuid');
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

}
