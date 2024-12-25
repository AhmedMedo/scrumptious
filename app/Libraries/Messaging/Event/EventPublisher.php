<?php

namespace App\Libraries\Messaging\Event;

use App\Libraries\Messaging\Aggregate\EventBridge\AggregateChanged;
use GlobIterator;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class EventPublisher
{
    /** @var string[]|null */
    private ?array $map = null;

    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param AggregateChanged $event
     *
     * @throws ReflectionException
     */
    public function release(AggregateChanged $event): void
    {
        foreach ($this->map($event) as $projectorName) {
            $projector = $this->container->get($projectorName);

            $reflection = new ReflectionMethod($projector, $this->methodName($event));
            $reflection->invoke($projector, $event);
        }
    }

    /**
     * @param AggregateChanged $event
     *
     * @return string[]
     */
    private function map(AggregateChanged $event): array
    {
        if (empty($this->map) === true) {
            $map = [];
            $pathPattern = app_path('Component/*/Resources/events/');

            $iterator = new GlobIterator($pathPattern . '*.php');
            $iterator->rewind();
            while ($iterator->valid() === true) {
                /** @var string[] $tmp */
                $tmp = include $iterator->current();
                $map = array_merge($map, $tmp);

                $iterator->next();
            }

            $this->map = $map;
        }

        return $this->map[$event->eventName()] ?? [];
    }

    /**
     * @param AggregateChanged $event
     *
     * @return string
     * @throws ReflectionException
     *
     */
    private function methodName(AggregateChanged $event): string
    {
        $reflectionClass = new ReflectionClass($event->eventName());

        return 'on' . $reflectionClass->getShortName();
    }
}
