<?php

namespace App\Libraries\Messaging\Saga\Metadata;

use App\Libraries\Messaging\Saga\SagaRoot;

interface MetadataFactory
{
    public function create(SagaRoot $saga): Metadata;
}
