<?php

namespace App\Components\Auth\Domain\Exception;

use App\Libraries\Base\Exception\BaseException;

class WrongCredentialsException extends BaseException
{
    protected string $id = 'wrong_credentials';

    public function __construct($message = "")
    {
        $message = !empty($message) ? $message : $this->buildFromConfig();
        parent::__construct($message , $this->status);
    }
}
