<?php

namespace App\Libraries\Base\Exception;

use Exception;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Response;
use ReflectionClass;

class InternalApiException extends Exception implements Arrayable, WithStackTrace
{
    /** @var int|null */
    protected $error_code = null;

    /**
     * @var ?string
     */
    protected $component = null;

    /**
     * @var string
     */
    protected $message = "";

    protected array $details = [];

    /**
     * @var bool
     */
    protected $attachStackTrace;

    /**
     * Internal details should not be forwarded to the client.
     * It should be only logged to developers.
     *
     * TODO We probably should remove this property and keep internal info in $details
     *      $errorCode should be enough to identify error
     *
     * @var string
     */
    protected $internalDetails;

    protected array $stack = [];

    /**
     * Intercepted exception
     */
    protected string $class;

    /**
     * @var mixed[]|mixed
     */
    private $stackTrace = null;

    /**
     * @param string|null $message
     * @param int $code
     * @param array|null $details
     */
    public function __construct(
        string $message = null,
        int $code = 0,
        array $details = null
    ) {
        $this->code = $code;
        $this->class = (new ReflectionClass($this))->getShortName();

        if ($details) {
            $this->details = $details;
        }

        $this->attachStackTrace = env('APP_DEBUG');
        $this->stackTrace = $this->getStackTrace();

        if ($this->code === 0) {
            $this->code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        $this->setMessage($message);
        parent::__construct($this->message, $code);
    }

    /**
     * @return array
     */
    public function getStackTrace(): array
    {
        if ($this->stackTrace) {
            return $this->stackTrace;
        }
        if (env('STACK_TRACE_SHORT')) {
            return explode("\n", $this->getTraceAsString());
        }

        $allowed = ['file', 'line', 'function', 'class', 'type'];

        return array_map(fn ($item): array => array_intersect_key($item, array_flip($allowed)), $this->getTrace());
    }

    public function setMessage(string $message): void
    {
        if ($this->message !== '' && $message) {
            $this->message .= ' ' . $message;
        }

        if (empty($this->message) && $message) {
            $this->message = $message;
        }
    }

    /**
     * Regenerate from the given data
     *
     * @param array $data
     * @static
     *
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $e = new self();
        $e->code = $data['status'] ?? $e->code;
        $e->message = $data['message'] ?? $e->message;
        //TODO: Implement this in the future
//        $e->component = $data['component'] ?? $e->component;
        $e->error_code = $data['error_code'] ?? $e->error_code;
        $e->stackTrace = $data['stackTrace'] ?? $e->stackTrace;
        $e->component = $data['component'] ?? $e->component;
        $e->details = $data['details'] ?? $e->details;
        $e->internalDetails = $data['internalDetails'] ?? $e->internalDetails;
        $e->stack = $data['stack'] ?? $e->stack;
        $e->class = $data['class'] ?? $e->class;

        return $e;
    }

    /**
     * @param mixed $data
     *
     * @return void
     */
    public function stackPush($data): void
    {
        $this->stack[] = $data;
    }

    /**
     * @return array{InternalApiException: true, class: string, message: string, error_code: int|null, internalDetails: string, status: mixed, component: string|null, details: mixed[], stack: mixed[], stackTrace?: mixed[]}
     */
    public function toArray(): array
    {
        $arr = [
            'InternalApiException' => true,
            'class'                => $this->class,
            'message'              => $this->message,
            'error_code'           => $this->error_code,
            'internalDetails'      => $this->internalDetails,
            'status'               => $this->code,
            'component'            => $this->component,
            'details'              => $this->details,
            'stack'                => $this->stack,
        ];

        if ($this->attachStackTrace) {
            $arr['stackTrace'] = $this->getStackTrace();
        }

        return $arr;
    }

    /**
     * Get details
     *
     * @return array
     */
    public function getDetails(): array
    {
        return $this->details;
    }

    /**
     * Add details
     *
     * @param array $details
     *
     * @return void
     */
    public function addDetails(array $details): void
    {
        $this->details = array_merge($this->details, $details);
    }

    /**
     * @param int $code
     *
     * @return $this
     */
    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param int|null $errorCode
     *
     * @return $this
     */
    public function setErrorCode(?int $errorCode): self
    {
        $this->error_code = $errorCode;

        return $this;
    }
}
