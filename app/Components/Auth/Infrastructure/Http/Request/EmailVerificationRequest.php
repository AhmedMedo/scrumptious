<?php

namespace App\Components\Auth\Infrastructure\Http\Request;

use App\Components\Auth\Data\Enums\UserVerificationTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request : 'EmailVerificationRequest',
    required: true,
    content : new OA\JsonContent(
        required  : ['verification_token'],
        properties: [
            new OA\Property(property: 'verification_token', type: 'string'),
        ],
    )
)]
class EmailVerificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'verification_token' => 'required|string',
        ];
    }

    public function token(): string
    {
        return $this->input('verification_token');
    }
}
