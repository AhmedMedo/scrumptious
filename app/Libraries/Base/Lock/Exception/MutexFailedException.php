<?php

namespace App\Libraries\Base\Lock\Exception;

use App\Libraries\Base\Exception\BaseException;

use function sprintf;

class MutexFailedException extends BaseException
{
    public function __construct(string $name)
    {
        $message = sprintf('Mutex for name %s failed.', $name);
        parent::__construct($message, 500, $previous = null);
    }
}
