<?php

namespace App\Components\Auth\Domain\Exception;

use App\Libraries\Base\Exception\BaseException;

class UserNotFoundException extends BaseException
{
    protected string $id = 'not_found';

    public function __construct(string $category = 'email')
    {
        $this->id = sprintf('%s_%s', $category, $this->id);
        parent::__construct($this->buildFromConfig(), $this->status);
    }
}
