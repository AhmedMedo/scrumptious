<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Overtrue\LaravelFavorite\Events\Favorited;
use Overtrue\LaravelFavorite\Events\Unfavorited;

/**
 * @property string $uuid
 * @property string $user_uuid
 * @property string $favoriteable_uuid
 * @property string $favoriteable_type
 * @property Model $user
 * @property Model $favoriter
 * @property Model $favoriteable
 */
class Favorite extends Model
{
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'uuid',
        'user_uuid',
        'favoriteable_uuid',
        'favoriteable_type',
    ];

    protected $dispatchesEvents = [
        'created' => Favorited::class,
        'deleted' => Unfavorited::class,
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = config('favorite.favorites_table');
        parent::__construct($attributes);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Set UUID if not provided
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::orderedUuid()->toString();
            }

            // Set user UUID from authenticated user if not provided
            $userForeignKey = config('favorite.user_foreign_key', 'user_uuid');
            if (empty($model->{$userForeignKey}) && auth()->check()) {
                $model->{$userForeignKey} = auth()->user()->getKey();
            }
        });
    }

    public function favoriteable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo(
            null,
            'favoriteable_type',
            'favoriteable_uuid'  // Custom column name for UUID
        );
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            config('favorite.favoriter_model'),
            config('favorite.user_foreign_key', 'user_uuid'),
            'uuid'  // Parent key on the related model
        );
    }

    public function favoriter(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->user();
    }

    public function scopeWithType(Builder $query, string $type): Builder
    {
        return $query->where('favoriteable_type', app($type)->getMorphClass());
    }
}
