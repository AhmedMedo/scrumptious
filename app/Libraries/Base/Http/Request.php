<?php

namespace App\Libraries\Base\Http;

use App\Component\Account\DomainModel\Auth\Enum\RoleEnum;
use App\Component\Content\Application\Country\Helper\CountryHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;

/**
 * @deprecated Use [Illuminate\Foundation\Http\FormRequest]
 */
abstract class Request extends FormRequest
{
    public function convertNumbersToEnglish(string ...$fields): void
    {
        $input = $this->all();

        foreach ($fields as $field) {
            if (array_key_exists($field, $input) && ! is_array($field)) {
                $input[$field] = convertToEnglishNumbers((string) $input[$field]);
            }
        }

        $this->replace($input);
    }

    /**
     * @return string[]
     */
    public function countryCodes(): array
    {
        return config('telgani.country_codes');
    }

    public function inCountryCodePhonesRule(): In
    {
        return Rule::in(
            $this->countryCodes()
        );
    }

    public function inBackendRolesRule(): In
    {
        return Rule::in(RoleEnum::backendRoles());
    }

    public function inCountryCodesRule(): In
    {
        return Rule::in(array_keys(
            $this->countryCodes()
        ));
    }

    public function phoneCountryCode(string $fieldName = 'country_code'): string
    {
        $fieldValue = (string) $this->input($fieldName);

        return CountryHelper::recognizeByPhonePrefix($fieldValue);
    }
}
