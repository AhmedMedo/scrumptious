<?php

namespace App\Libraries\Valuing\Char;

use App\Libraries\Valuing\VO;
use InvalidArgumentException;

use function filter_var;

final class Email extends VO
{
    /**
     * @param string $email
     *
     * @return Email
     * @throws InvalidArgumentException
     *
     */
    public static function fromString(string $email): Email
    {
        return new self($email);
    }

    /**
     * @inheritdoc
     */
    protected function guard($email): void
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException('Invalid Email string: ' . $email);
        }
    }
}
