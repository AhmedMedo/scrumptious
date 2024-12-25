<?php

namespace App\Libraries\Valuing\Map;

use App\Libraries\Valuing as VO;
use App\Libraries\Valuing\Char\Text;
use phpDocumentor\Reflection\Types\Static_;

abstract class Morph extends VO\VO
{
    private ?Text $type = null;

    /** @var mixed|null */
    private $id;

    /**
     * @param string $type
     * @param string|int $id
     *
     * @return static
     */
    public static function fromData(string $type, $id): self
    {
        // @phpstan-ignore-next-line
        return new static([
            'type' => $type,
            'id'   => $id,
        ]);
    }

    public function type(): string
    {
        return $this->type->toString();
    }

    /**
     * @return string|int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    protected function guard($value): void
    {
        $this->type = Text::fromString($value['type']);
        $this->id = $value['id'];
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        return json_encode($this->value);
    }
}
