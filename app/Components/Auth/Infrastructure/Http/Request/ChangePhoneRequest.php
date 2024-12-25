<?php

namespace App\Components\Auth\Infrastructure\Http\Request;

use App\Rules\OldPasswordRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request : 'ChangePhoneRequest',
    required: true,
    content : new OA\JsonContent(
        required  : ['country_code','phone_number'],
        properties: [
            new OA\Property(property: 'country_code', type: 'string'),
            new OA\Property(property: 'phone_number', type: 'string'),
        ],
    )
)]

class ChangePhoneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'country_code' => 'required|string',
            'phone_number' => 'required|string',
        ];
    }

    public function countryCode(): string
    {
        return $this->input('country_code');
    }

    public function phoneNumber(): string
    {
        return $this->input('phone_number');
    }

}
