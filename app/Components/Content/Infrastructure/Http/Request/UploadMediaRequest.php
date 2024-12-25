<?php

namespace App\Components\Content\Infrastructure\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request : 'UploadMediaRequest',
    required: true,
    content : new OA\JsonContent(
        required  : ['files'],
        properties: [
            new OA\Property(property: 'files', description: 'File', type: 'array', items: new OA\Items(type: 'string', format: 'binary'), example: 'files/temp/1.jpg'),
        ],
    )
)]
class UploadMediaRequest extends FormRequest
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
            'files' => 'required|array|max:10',
            'files.*' => 'required|max:20000',
        ];
    }
}
