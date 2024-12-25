<?php

namespace App\Components\Auth\Infrastructure\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request : 'LoginRequest',
    required: true,
    content : new OA\JsonContent(
        required  : ['username', 'password'],
        properties: [
            new OA\Property(property: 'username', description: 'email or phone number', type: 'string'),
            new OA\Property(property: 'password', type: 'string'),
        ],
    )
)]
class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username'  => 'required|string',
            'password' => 'required|string',
        ];
    }
}
