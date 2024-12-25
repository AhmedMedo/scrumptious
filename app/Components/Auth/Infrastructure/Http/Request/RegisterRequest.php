<?php

namespace App\Components\Auth\Infrastructure\Http\Request;

use App\Rules\UserPhoneCountRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request : 'RegisterRequest',
    required: true,
    content : new OA\JsonContent(
        required  : ['email', 'password','first_name','last_name','phone_number'],
        properties: [
            new OA\Property(property: 'first_name', type: 'string'),
            new OA\Property(property: 'last_name', type: 'string'),
            new OA\Property(property: 'email', type: 'string'),
            new OA\Property(property: 'password', type: 'string'),
            new OA\Property(property: 'password_confirmation', type: 'string'),
            new OA\Property(property: 'country', type: 'string'),
            new OA\Property(property: 'country_code', type: 'string'),
            new OA\Property(property: 'phone_number', type: 'string'),
        ],
    )
)]

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'email'  => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|max:255|confirmed',
            'country_uuid' => 'nullable|string',
            'country_code' => 'nullable|string',
            'phone_number' => [
                'nullable',
            ],
            'address' => 'nullable|string',
        ];
    }
}
