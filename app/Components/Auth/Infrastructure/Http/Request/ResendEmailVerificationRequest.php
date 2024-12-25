<?php

namespace App\Components\Auth\Infrastructure\Http\Request;

use App\Components\Auth\Data\Enums\UserVerificationTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request : 'ResendEmailVerificationRequest',
    required: true,
    content : new OA\JsonContent(
        required  : ['email'],
        properties: [
            new OA\Property(property: 'email', type: 'string'),
        ],
    )
)]
class ResendEmailVerificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
        ];
    }

    public function email(): string
    {
        return $this->input('email');
    }
}
