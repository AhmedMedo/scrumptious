<?php

namespace App\Libraries\Base\Model\HasTimestamp;

use Illuminate\Database\Eloquent\Model;

trait HasCreatedTimestampTrait
{
    public static function bootHasCreatedTimestampTrait(): void
    {
        static::creating(function (Model $model): void {
            $createdFieldName = Model::CREATED_AT;
            if (empty($model->getAttribute($createdFieldName)) === true) {
                $model->setCreatedAt($model->freshTimestamp());
            }
        });
    }
}
