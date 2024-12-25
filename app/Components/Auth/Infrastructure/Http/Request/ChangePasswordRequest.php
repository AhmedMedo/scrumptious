<?php

namespace App\Components\Auth\Infrastructure\Http\Request;

use App\Rules\OldPasswordRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request : 'ChangePasswordRequest',
    required: true,
    content : new OA\JsonContent(
        required  : ['old_password','new_password'],
        properties: [
            new OA\Property(property: 'old_password', description: 'Old Password', type: 'string'),
            new OA\Property(property: 'password', description: 'New Password', type: 'string'),
            new OA\Property(property: 'password_confirmation', description: 'Confirm Password', type: 'string'),
        ],
    )
)]

class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'old_password'  => 'required|string',
            'password'  => [
                'required',
                'string',
                'min:8',
                'confirmed',
                new OldPasswordRule()
            ],
        ];
    }

    public function oldPassword()
    {
        return $this->input('old_password');
    }

    public function password()
    {
        return $this->input('password');
    }

    public function passwordConfirmation()
    {
        return $this->input('password_confirmation');
    }
}
