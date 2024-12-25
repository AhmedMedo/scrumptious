<?php

namespace App\Libraries\Validation\Rules;

use Illuminate\Contracts\Validation as Contract;
use Illuminate\Support\Facades\Validator;

final class PromoCode implements Contract\Rule, Contract\ImplicitRule
{
    private const MIN_LENGTH = 1;
    private const MAX_LENGTH = 75;

    private bool $isRequired;

    private function __construct(bool $isRequired)
    {
        $this->isRequired = $isRequired;
    }

    public static function required(): PromoCode
    {
        return new self(true);
    }

    public static function nullable(): PromoCode
    {
        return new self(false);
    }

    /**
     * @param string|mixed $attribute
     * @param string|mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return $this->validator((string) $attribute, (string) $value)->passes();
    }

    public function message(): string
    {
        return 'Invalid promo code';
    }

    private function presenceRule(): string
    {
        return $this->isRequired ? 'required' : 'nullable';
    }

    private function validator(string $attribute, string $value): Contract\Validator
    {
        return Validator::make(
            [$attribute => $value],
            [$attribute => $this->toArray()]
        );
    }

    private function toArray(): array
    {
        return array_filter([
            $this->presenceRule(),
            'string',
            'alpha_num',
            sprintf('min:%s', self::MIN_LENGTH),
            sprintf('max:%s', self::MAX_LENGTH),
        ]);
    }
}
