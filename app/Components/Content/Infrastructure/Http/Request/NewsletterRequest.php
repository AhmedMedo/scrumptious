<?php

namespace App\Components\Content\Infrastructure\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request : 'NewsletterRequest',
    required: true,
    content : new OA\JsonContent(
        required  : ['email'],
        properties: [
            new OA\Property(property: 'email', description: 'email', type: 'string'),
        ],
    )
)]
class NewsletterRequest extends FormRequest
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
            'email' => 'required|email',
        ];
    }
}
