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
        return [
            'first_name'  => 'required|string',
            'last_name'  => 'required|string',
            'address'  => 'nullable|string',
            'postal_code'  => 'nullable|string',
            'country_uuid'  => 'nullable|string',
            'image'  => 'nullable|array',
            'image.file_path'  => 'nullable|string',
            'image.file_url'  => 'nullable|string',
            'image.file_name'  => 'nullable|string',
            'image.mime_type'  => 'nullable|string',
            'phone' => 'nullable|numeric|unique:users,phone_number'.($this->user('api')->phone_number ? ','.$this->user('api')->uuid.',uuid' : ''),
            'email' => 'nullable|email|unique:users,email'.($this->user('api')->email ? ','.$this->user('api')->uuid.',uuid' : ''),
        ];
    }

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
            'first_name' => $this->firstName(),
            'last_name' => $this->lastName(),
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

        return $data;
    }
}
