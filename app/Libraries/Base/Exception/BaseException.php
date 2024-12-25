<?php

namespace App\Libraries\Base\Exception;

use Exception;
use Throwable;

class BaseException extends Exception
{
    protected string $id = '';

    protected string $translationKey = '';

    protected int $status = 200;

    protected int $error_code = 0;

    protected string $title = '';

    protected $message;

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->status = $code;
        $this->setMessage($message);
        parent::__construct($this->message, $code, $previous);
    }

    public function setMessage(string $message): void
    {
        if (empty($message)) {
            $message = trans('api_exception.' . $this->error_code);
        }

        $this->message = $message;
    }

    public function getStatus(): int
    {
        return (int) $this->status;
    }

    /**
     * @return array{id: string, status: string, error_code: int, title: string, message: string, translation: string}
     */
    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'status'      => $this->status,
            'error_code'  => $this->error_code,
            'title'       => $this->title,
            'message'     => $this->message,
            'translation' => $this->getTranslation(),
        ];
    }

    public function getTranslation(): string
    {
        if (! $this->translationKey) {
            return $this->message;
        }

        return trans($this->translationKey);
    }

    protected function buildFromConfig(): string
    {
        $error = config(sprintf('exceptions.%s', $this->id));

        $this->title = (string) $error['title'];
        $this->status = (int) $error['status'];
        $this->message = (string) $error['message'];
        $this->translationKey = (string) ($error['translationKey'] ?? '');

        return $this->message;
    }
}
