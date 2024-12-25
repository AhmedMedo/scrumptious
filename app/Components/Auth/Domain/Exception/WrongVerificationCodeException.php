<?php

namespace App\Components\Auth\Domain\Exception;

use App\Libraries\Base\Exception\BaseException;

class WrongVerificationCodeException extends BaseException
{
    protected string $id = 'wrong_verification_code';

    public function __construct()
    {
        parent::__construct($this->buildFromConfig(), $this->status);
    }
}
