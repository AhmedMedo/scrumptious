<?php

namespace App\Libraries\Base\Model\HasUuid;

interface HasUuid
{
    public function getUuidFieldName(): string;

    public function getUuid(): string;
}
