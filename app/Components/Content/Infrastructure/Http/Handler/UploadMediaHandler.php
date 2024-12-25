<?php

namespace App\Components\Content\Infrastructure\Http\Handler;

use App\Components\Content\Infrastructure\Http\Request\UploadMediaRequest;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use OpenApi\Attributes as OA;

#[OA\POST(
    path: '/api/v1/content/upload-media',
    description: 'Upload Media',
    summary: 'Upload Media',
    requestBody: new OA\RequestBody('#/components/requestBodies/UploadMediaRequest'),
    tags: ['Content'],
    responses: [
        new OA\Response(response: 200, description: 'success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(properties: [
                    new OA\Property(property: 'file_path', type: 'string', example: 'files/temp/1.jpg'),
                    new OA\Property(property: 'file_url', type: 'string', example: 'http://localhost:8000/storage/files/temp/1.jpg'),
                    new OA\Property(property: 'file_name', type: 'string', example: '1.jpg'),
                    new OA\Property(property: 'mime_type', type: 'string', example: 'image/jpeg'),
                ])),
            ],
            type      : 'object'
        )),
    ]
)]

class UploadMediaHandler extends Handler
{
    public function __invoke(UploadMediaRequest $request): JsonResponse
    {

        $response = [];
        foreach ($request->file('files') as $fileItem) {
            $storagePath = Storage::disk('public')->put('/files/temp', $fileItem, 'public');
            $response[] = [
                'file_path' => $storagePath,
                'file_url'  => Storage::disk('public')->url($storagePath),
                'file_name' => $fileItem->getClientOriginalName(),
                'mime_type' => $fileItem->getClientMimeType(),
            ];
        }

        return $this->successResponseWithData($response);
    }
}
