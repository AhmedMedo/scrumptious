<?php

namespace App\Libraries\Base\Model\HasTimestamp;

use Illuminate\Database\Eloquent\Model;

trait HasUpdatedTimestampTrait
{
    public static function bootHasUpdatedTimestampTrait(): void
    {
        static::creating(function (Model $model): void {
            $updatedFieldName = Model::UPDATED_AT;
            if (empty($model->getAttribute($updatedFieldName)) === true) {
                $model->setUpdatedAt($model->freshTimestamp());
            }
        });
    }
}
