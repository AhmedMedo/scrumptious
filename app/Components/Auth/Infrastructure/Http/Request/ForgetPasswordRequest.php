<?php

namespace App\Components\Auth\Infrastructure\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request : 'ForgetPasswordRequest',
    required: true,
    content : new OA\JsonContent(
        required  : ['email'],
        properties: [
            new OA\Property(property: 'username', description: 'Email or phone', type: 'string'),
        ],
    )
)]

class ForgetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username'  => 'required|string',
        ];
    }
}
