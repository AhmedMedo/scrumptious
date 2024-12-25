<?php

namespace App\Libraries\Messaging\Saga\Metadata;

use App\Libraries\Messaging\Saga\SagaRoot;

final class SagaMetadataFactory implements MetadataFactory
{
    /**
     * {@inheritdoc}
     */
    public function create(SagaRoot $saga): Metadata
    {
        return new Metadata($saga);
    }
}
