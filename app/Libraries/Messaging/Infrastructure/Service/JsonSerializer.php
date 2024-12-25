<?php

namespace App\Libraries\Messaging\Infrastructure\Service;

use RuntimeException;

class JsonSerializer
{
    /** @var string[] */
    private array $errors = [
        JSON_ERROR_NONE           => 'No error has occurred',
        JSON_ERROR_DEPTH          => 'The maximum stack depth has been exceeded',
        JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
        JSON_ERROR_CTRL_CHAR      => 'Control character error, possibly incorrectly encoded',
        JSON_ERROR_SYNTAX         => 'Syntax error',
        JSON_ERROR_UTF8           => 'Malformed UTF-8 characters, possibly incorrectly encoded',
    ];

    /**
     * @inheritdoc
     */
    public function encode(array $data): string
    {
        $result = json_encode($data, JSON_UNESCAPED_UNICODE);

        if ($result === false) {
            throw new RuntimeException($this->errors[json_last_error()]);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function decode(string $json): array
    {
        $result = json_decode($json, true);

        if ($result === false) {
            throw new RuntimeException($this->errors[json_last_error()]);
        }

        return $result ?: [];
    }
}
