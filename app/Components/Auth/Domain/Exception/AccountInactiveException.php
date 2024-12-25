<?php

namespace App\Components\Auth\Domain\Exception;

use App\Libraries\Base\Exception\BaseException;

class AccountInactiveException extends BaseException
{
    protected string $id = 'account_inactive';


    public function __construct($message = "")
    {
        parent::__construct($message ?? $this->buildFromConfig(), $this->status);
    }
}
