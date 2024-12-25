<?php

namespace App\Libraries\Base\Model\HasUuid;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasUuidTrait
{
    protected string $uuidFieldName = 'uuid';

    public static function bootHasUuidTrait(): void
    {
        static::creating(function ($model): void {
            $uuidFieldName = $model->getUuidFieldName();

            if (empty($model->$uuidFieldName)) {
                $model->$uuidFieldName = static::generateUuid();
            }
        });
    }

    public static function generateUuid(): string
    {
        return Str::uuid()->toString();
    }

    public static function findByUuid(string $uuid): ?Model
    {
        return static::query()
            ->where('uuid', '=', $uuid)
            ->first();
    }

    public static function getByUuid(string $uuid): Model
    {
        return static::query()
            ->where('uuid', '=', $uuid)
            ->firstOrFail();
    }

    public function scopeByUuid(Builder $query, string $uuid): Builder
    {
        return $query->where($this->getUuidFieldName(), $uuid);
    }

    public function getUuidFieldName(): string
    {
        if (! empty($this->uuidFieldName)) {
            return $this->uuidFieldName;
        }

        return $this->getKeyName();
    }

    public function getUuid(): string
    {
        return $this->getAttribute($this->getUuidFieldName());
    }
}
