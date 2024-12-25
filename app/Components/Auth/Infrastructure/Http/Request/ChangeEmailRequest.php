<?php

namespace App\Components\Auth\Infrastructure\Http\Request;

use App\Rules\OldPasswordRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request : 'ChangeEmailRequest',
    required: true,
    content : new OA\JsonContent(
        required  : ['email'],
        properties: [
            new OA\Property(property: 'email', description: 'email', type: 'string'),
        ],
    )
)]

class ChangeEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required','email','unique:users,email'],
        ];
    }

    public function email()
    {
        return $this->input('email');
    }

}
