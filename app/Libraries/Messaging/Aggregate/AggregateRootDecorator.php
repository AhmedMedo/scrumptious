<?php

namespace App\Libraries\Messaging\Aggregate;

use App\Libraries\Messaging\Aggregate\EventBridge\AggregateChanged;
use ArrayIterator;
use InvalidArgumentException;
use Webmozart\Assert\Assert as Assertion;

class AggregateRootDecorator extends AggregateRoot
{
    /**
     * @return AggregateRootDecorator
     */
    public static function newInstance(): self
    {
        // @phpstan-ignore-next-line
        return new static();
    }

    /**
     * @param string $aggregateRootClass
     *
     * @throws InvalidArgumentException
     */
    private function assertAggregateRootExistence(string $aggregateRootClass): void
    {
        Assertion::classExists($aggregateRootClass, 'Aggregate root class cannot be found. Got: %s');
    }

    /**
     * @param string $aggregateType
     * @param AggregateId $aggregateId
     *
     * @return AggregateRoot
     * @throws InvalidArgumentException
     *
     */
    public function fromAggregateData(string $aggregateType, AggregateId $aggregateId): AggregateRoot
    {
        $aggregateRoot = $this->fromHistory($aggregateType, new ArrayIterator());
        $aggregateRoot->setAggregateId($aggregateId);

        return $aggregateRoot;
    }

    public function fromHistory(string $aggregateRootClass, ArrayIterator $aggregateChangedEvents): AggregateRoot
    {
        $this->assertAggregateRootExistence($aggregateRootClass);

        return $aggregateRootClass::reconstituteFromHistory($aggregateChangedEvents);
    }

    /**
     * @param AggregateRoot $aggregateRoot
     *
     * @return AggregateChanged[]
     */
    public function extractRecordedEvents(AggregateRoot $aggregateRoot): array
    {
        return $aggregateRoot->popRecordedEvents();
    }
}
