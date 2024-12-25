<?php

namespace App\Libraries\Base\Exception;

interface WithStackTrace
{
    /**
     * @return array
     */
    public function getStackTrace(): array;
}
