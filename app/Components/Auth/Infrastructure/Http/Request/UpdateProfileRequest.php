<?php

namespace App\Components\Auth\Infrastructure\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request : 'UpdateProfileRequest',
    required: true,
    content : new OA\JsonContent(
        required  : ['first_name','last_name'],
        properties: [
            new OA\Property(property: 'first_name', description: 'First Name', type: 'string'),
            new OA\Property(property: 'last_name', description: 'Last Name', type: 'string'),
            new OA\Property(property: 'address', description: 'Address', type: 'string'),
            new OA\Property(property: 'postal_code', description: 'Postal Code', type: 'string'),
            new OA\Property(property: 'country_uuid', description: 'Country Uuid', type: 'string'),
            new OA\Property(property: 'image', description: 'User Image', properties: [
                new OA\Property(property: 'file_path', type: 'string'),
                new OA\Property(property: 'file_url', type: 'string'),
                new OA\Property(property: 'file_name', type: 'string'),
                new OA\Property(property: 'mime_type', type: 'string'),
            ], type: 'object'),

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
            new OA\Property(property: 'phone', description: 'Phone', type: 'string'),
            new OA\Property(property: 'email', description: 'Email', type: 'string'),
            new OA\Property(property: 'gender', description: 'Gender', type: 'string'),
        ],
    )
)]

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->user('api');

        return [
            'first_name'       => 'required|string',
            'last_name'        => 'required|string',
            'address'          => 'nullable|string',
            'postal_code'      => 'nullable|string',
            'country_uuid'     => 'nullable|string',
            'image'            => 'nullable|array',
            'image.file_path'  => 'nullable|string',
            'image.file_url'   => 'nullable|string',
            'image.file_name'  => 'nullable|string',
            'image.mime_type'  => 'nullable|string',

            'phone'            => [
                'nullable',
                'numeric',
                'unique:users,phone_number' . ($user && $user->phone_number ? ',' . $user->uuid . ',uuid' : ''),
            ],
            'email'            => [
                'nullable',
                'email',
                'unique:users,email' . ($user && $user->email ? ',' . $user->uuid . ',uuid' : ''),
            ],

            // New fields validation
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
            'gender'           => 'nullable|string',
        ];
    }

    // Keep existing getters...
    public function firstName()
    {
        return $this->input('first_name');
    }

    public function lastName()
    {
        return $this->input('last_name');
    }

    public function imagePath()
    {
        return $this->input('image.file_path');
    }

    public function address()
    {
        return $this->input('address');
    }

    public function postalCode()
    {
        return $this->input('postal_code');
    }

    public function countryUuid()
    {
        return $this->input('country_uuid');
    }


    public function toArray(): array
    {
        $data =  [
            'first_name' => $this->input('first_name'),
            'last_name' => $this->input('last_name'),
            'address' => $this->input('address'),
            'postal_code' => $this->input('postal_code'),
            'country_uuid' => $this->input('country_uuid'),
        ];

        if ($this->input('phone')) {
            $data['phone_number'] = $this->input('phone');
        }

        if ($this->input('email')) {
            $data['email'] = $this->input('email');
        }

        // Add new fields if present
        foreach ([
                     'birth_date',
                     'weight',
                     'weight_unit',
                     'height',
                     'height_unit',
                     'user_diet',
                     'goal',
                     'have_allergies',
                     'allergies',
                     'gender',
                 ] as $field) {
            if ($this->has($field)) {
                $data[$field] = $this->input($field);
            }
        }

        return $data;
    }
}
