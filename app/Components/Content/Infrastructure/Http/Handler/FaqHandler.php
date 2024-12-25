<?php

namespace App\Components\Content\Infrastructure\Http\Handler;

use App\Components\Content\Data\Entity\FAQ;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/v1/content/faq',
    description: 'Get faq',
    summary: 'Get faq',
    tags: ['Content'],
    parameters: [
        new OA\Parameter(name: 'type', description: 'Faq Filter', in: 'query', required: false, example: 'general'),
    ],
    responses  : [
        new OA\Response(response: 200, description: 'success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(properties: [
                    new OA\Property(property: 'uuid', type: 'string'),
                    new OA\Property(property: 'question', type: 'string'),
                    new OA\Property(property: 'answer', type: 'string'),
                    new OA\Property(property: 'type', type: 'string'),
                    new OA\Property(property: 'created_at', type: 'string'),
                ])),
            ],
            type      : 'object'
        )),
    ]
)]
class FaqHandler extends Handler
{

    public function __invoke(Request $request): JsonResponse
    {
        return $this->successResponseWithData(
                FAQ::query()->when($request->has('type'), function ($query) use ($request) {
                    $query->where('type', $request->get('type'));
                },function ($query){
                    $query->where('type', 'general');
                })->where('is_active','=',true)->get()->toArray()
        );
    }
}
