<?php

namespace App\Libraries\Messaging;

interface Message
{
    public function messageName(): string;
}
