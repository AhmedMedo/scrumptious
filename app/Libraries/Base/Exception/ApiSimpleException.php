<?php

/**
 * Date: 23/05/2017
 * Time: 00:01
 *
 * @author  Artur Bartczak <artur.bartczak@code4.pl>
 *
 * @package CODE4 Boilerplate
 */

namespace App\Libraries\Base\Exception;

use Exception;

class ApiSimpleException extends Exception
{
    protected int $status;

    /**
     *
     * @param string $message
     * @param int $code
     * @param null $previous
     */
    public function __construct(string $message = "", int $code = 500, $previous = null)
    {
        $this->status = $code;
        parent::__construct($message, $this->status, $previous);
    }
}
