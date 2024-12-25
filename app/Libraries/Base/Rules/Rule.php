<?php

namespace App\Libraries\Base\Rules;

use Illuminate\Contracts\Validation\Rule as BaseRule;

abstract class Rule implements BaseRule
{
    protected array $parameters;

    public function __construct()
    {
        $this->parameters = func_get_args();
    }

    public function getLocalizedErrorMessage(string $key, string $default): string
    {
        return trans("validation.$key") === "validation.$key" ? $default : trans("validation.$key");
    }
}
