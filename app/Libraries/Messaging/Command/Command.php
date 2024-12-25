<?php

namespace App\Libraries\Messaging\Command;

use App\Libraries\Messaging\Message;

use function get_called_class;

abstract class Command implements Message
{
    /**
     * @inheritdoc
     */
    public function messageName(): string
    {
        return get_called_class();
    }
}
