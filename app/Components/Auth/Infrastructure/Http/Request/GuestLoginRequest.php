<?php

namespace App\Components\Auth\Infrastructure\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request : 'GuestLoginRequest',
    required: true,
    content : new OA\JsonContent(
        required  : ['name', 'email'],
        properties: [
            new OA\Property(property: 'name', description: 'name', type: 'string'),
            new OA\Property(property: 'email', type: 'string'),
        ],
    )
)]
class GuestLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => 'required|string',
            'email'  => 'required|string|email',
            'phone'   => 'required|string'
        ];
    }

    public function name(): string
    {
        return $this->input('name');
    }

    public function email(): string
    {
        return $this->input('email');
    }

    public function phone()
    {
        return $this->input('phone');
    }
}
