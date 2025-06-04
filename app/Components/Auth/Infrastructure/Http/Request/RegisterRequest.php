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
            new OA\Property(property: 'country_uuid', type: 'string'),
            new OA\Property(property: 'country_code', type: 'string'),
            new OA\Property(property: 'phone_number', type: 'string'),
            // New optional fields
            new OA\Property(property: 'birth_date', description: 'Birth Date', type: 'string', format: 'date'),
            new OA\Property(property: 'weight', description: 'Weight', type: 'number', format: 'float'),
            new OA\Property(property: 'weight_unit', description: 'Weight Unit', type: 'string'),
            new OA\Property(property: 'height', description: 'Height', type: 'number', format: 'float'),
            new OA\Property(property: 'height_unit', description: 'Height Unit', type: 'string'),
            new OA\Property(property: 'user_diet', description: 'User Diet', type: 'string'),
            new OA\Property(property: 'goal', description: 'Goal', type: 'string'),
            new OA\Property(property: 'have_allergies', description: 'Has Allergies', type: 'boolean'),
            new OA\Property(property: 'allergies', description: 'Allergies List', type: 'array', items: new OA\Items(type: 'string')),
            new OA\Property(property: 'gender', description: 'Gender', type: 'string'),

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
            'birth_date'       => 'nullable|date',
            'weight'           => 'nullable|numeric',
            'weight_unit'      => 'nullable|string|max:10',
            'height'           => 'nullable|numeric',
            'height_unit'      => 'nullable|string|max:10',
            'user_diet'        => 'nullable|string',
            'goal'             => 'nullable|string',
            'have_allergies'   => 'nullable|boolean',
            'allergies'        => 'nullable|array',
            'allergies.*'      => 'string',
            'gender'           => 'nullable|string|max:10',
        ];
    }
}
