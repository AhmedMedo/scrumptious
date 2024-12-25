<?php

namespace App\Libraries\Messaging\Aggregate\EventBridge;

use App\Libraries\Messaging\Aggregate\AggregateId;
use App\Libraries\Messaging\Aggregate\AggregateRoot;
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
    private function assertAggregateRootExistence(AggregateRoot $aggregateRootClass): void
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
        /** @var AggregateRoot $aggregateRootClass * */
        $aggregateRootClass = $aggregateType;
        $this->assertAggregateRootExistence($aggregateRootClass);

        $aggregateRoot = $aggregateRootClass::reconstituteFromHistory(new ArrayIterator());
        $aggregateRoot->setAggregateId($aggregateId);

        return $aggregateRoot;
    }
}
