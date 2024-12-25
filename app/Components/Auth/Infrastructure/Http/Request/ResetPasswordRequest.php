<?php

namespace App\Components\Auth\Infrastructure\Http\Request;

use App\Rules\OldPasswordRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request : 'ResetPasswordRequest',
    required: true,
    content : new OA\JsonContent(
        required  : ['forget_password_token','password','password_confirmation'],
        properties: [
            new OA\Property(property: 'token', type: 'string'),
            new OA\Property(property: 'password', type: 'string'),
            new OA\Property(property: 'password_confirmation', type: 'string'),
        ],
    )
)]

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token'  => 'required|string|exists:user_verification,token',
            'password'  => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ];
    }
}
