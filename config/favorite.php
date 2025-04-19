<?php

return [
    /**
     * Use uuid as primary key.
     */
    'uuids' => true,

    /*
     * User tables foreign key name.
     */
    'user_foreign_key' => 'user_uuid',

    /*
     * Table name for favorites records.
     */
    'favorites_table' => 'favorites',

    /*
     * Model name for favorite record.
     */
    'favorite_model' => App\Models\Favorite::class,

     /*
     * Model name for favoriter model.
     */
    'favoriter_model' => \App\Components\Auth\Data\Entity\UserEntity::class,
];
