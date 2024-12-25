<?php

namespace App\Libraries\Messaging\Command;

interface CommandHandler
{
    public function run(Command $command): void;
}
