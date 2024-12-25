<?php

namespace App\Components\Auth\Infrastructure\Http\Request;

use App\Components\Auth\Data\Enums\UserVerificationTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request : 'UserVerificationRequest',
    required: true,
    content : new OA\JsonContent(
        required  : ['token','otp'],
        properties: [
            new OA\Property(property: 'token', type: 'string'),
            new OA\Property(property: 'otp', type: 'string'),
            new OA\Property(property: 'is_change_mobile', type: 'boolean'),
        ],
    )
)]
class UserVerificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => 'required|string',
            'otp' => 'required|string',
            'is_change_mobile' => ['nullable', 'boolean'],
        ];
    }
}
