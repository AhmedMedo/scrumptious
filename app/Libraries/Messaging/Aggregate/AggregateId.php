<?php

namespace App\Libraries\Messaging\Aggregate;

interface AggregateId
{
    public function toString(): string;
}
