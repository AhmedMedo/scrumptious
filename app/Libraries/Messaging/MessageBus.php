<?php

namespace App\Libraries\Messaging;

use Psr\Container\ContainerInterface;

final class MessageBus
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function handle(Command\Command $command): void
    {
        $handlerName = $command->messageName() . 'Handler';

        $handler = $this->container->make($handlerName);
        assert($handler instanceof Command\CommandHandler);
        $handler->run($command);
    }
}
