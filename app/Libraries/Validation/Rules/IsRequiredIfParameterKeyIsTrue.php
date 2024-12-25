<?php

namespace App\Libraries\Validation\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class IsRequiredIfParameterKeyIsTrue implements Rule
{
    private string $parameterKey;

    private Request $request;

    public function __construct(Request $request, string $parameterKey)
    {
        $this->request = $request;
        $this->parameterKey = $parameterKey;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param mixed $attribute
     * @param mixed $value
     */
    public function passes($attribute, $value): bool
    {
        $keyParameter = $this->parameterKey;
        if ($this->request->has($keyParameter) && $this->request->$keyParameter === true) {
            return (bool) $value;
        }

        return true;
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'The :attribute must be true.';
    }
}
