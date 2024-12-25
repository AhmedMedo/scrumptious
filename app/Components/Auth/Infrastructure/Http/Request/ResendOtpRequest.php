<?php

namespace App\Components\Auth\Infrastructure\Http\Request;

use App\Components\Auth\Data\Enums\UserVerificationTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request: 'ResendOtpRequest',
    required: true,
    content: new OA\JsonContent(
        required: ['token', 'type'],
        properties: [
            new OA\Property(property: 'token', type: 'string'),

        ],
    )
)]
class ResendOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => 'required|string',];
    }

    public function token(): string
    {
        return $this->input('token');
    }
}
