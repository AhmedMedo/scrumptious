<?php

namespace App\Components\Content\Infrastructure\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request : 'CustomerSupportRequest',
    required: true,
    content : new OA\JsonContent(
        required  : ['full_name', 'email', 'message'],
        properties: [
            new OA\Property(property: 'full_name', description: 'name', type: 'string'),
            new OA\Property(property: 'email', description: 'email', type: 'string'),
            new OA\Property(property: 'message', description: 'message', type: 'string'),
        ],
    )
)]
class CustomerSupportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array{image: string}
     */
    public function rules(): array
    {
        return [
            'full_name' => 'required|string',
            'email' => 'required|email',
            'message' => 'required|string',
        ];
    }
}
