<?php

/**
 * Date: 23/05/2017
 * Time: 00:01
 *
 * @author Artur Bartczak <artur.bartczak@code4.pl>
 *
 * @package CODE4 Boilerplate
 */

namespace App\Libraries\Base\Exception;

use Exception;
use Illuminate\Contracts\Translation\Translator;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\ResourceAbstract;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\TransformerAbstract;

// phpcs:ignore CodingStandard.Exceptions.ExceptionDeclaration.NotEndingWithException
abstract class BaseExceptionLegacy extends Exception
{
    protected ?string $id = null;

    /** @var string Translation key */
    protected $translationKey;

    protected string $status;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $message;

    /**
     * Transformer for more data-rich exceptions
     *
     * @var
     */
    protected $tranformer;

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

    /**
     * Get the status
     */
    public function getStatus(): int
    {
        return (int) $this->status;
    }

    /**
     * Return the Exception as an array
     *
     * @return array{id: string, status: string, title: string, message: string, translation: mixed[]|Translator|string|null}
     */
    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'status'      => $this->status,
            'title'       => $this->title,
            'message'     => $this->message,
            'translation' => $this->getTranslation(),
        ];
    }

    /**
     * @return array|Translator|null|string
     */
    public function getTranslation()
    {
        if (! $this->translationKey) {
            return $this->message;
        }

        return trans($this->translationKey);
    }

    /**
     * Build the Exception from config
     *
     * @return string
     */
    protected function buildFromConfig()
    {
        $error = config(sprintf('exceptions.%s', $this->id));

        $this->title = $error['title'];
        $this->status = $error['status'];
        $this->message = $error['message'];
        $this->translationKey = array_key_exists('translationKey', $error) ? $error['translationKey'] : null;

        return $this->message;
    }

    /**
     * Create the response for an item.
     *
     * @param mixed $item
     * @param TransformerAbstract $transformer
     *
     * @return array
     */
    protected function buildItemResponse($item, TransformerAbstract $transformer): array
    {
        $resource = new Item($item, $transformer);

        return $this->buildResourceResponse($resource);
    }

    /**
     * Create the response for a resource.
     *
     * @param ResourceAbstract $resource
     *
     * @return array
     */
    protected function buildResourceResponse(ResourceAbstract $resource): array
    {
        $fractal = app('League\Fractal\Manager');
        $fractal->setSerializer(new ArraySerializer());

        return $fractal->createData($resource)->toArray();
    }

    /**
     * Create the response for a collection.
     *
     * @param mixed $collection
     * @param TransformerAbstract $transformer
     *
     * @return array
     */
    protected function buildCollectionResponse($collection, TransformerAbstract $transformer): array
    {
        $resource = new Collection($collection, $transformer);

        return $this->buildResourceResponse($resource);
    }
}
