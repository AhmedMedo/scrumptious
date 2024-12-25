<?php

namespace App\Components\Content\Infrastructure\Http\Handler;

use App\Components\Content\Data\Entity\Partner;
use App\Components\Content\Infrastructure\Http\Request\PartnersRequest;
use App\Libraries\Base\Database\MySQL\ConnectionService;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\POST(
    path: '/api/v1/partners',
    description: 'Partners',
    summary: 'Partners',
    requestBody: new OA\RequestBody('#/components/requestBodies/PartnersRequest'),
    tags: ['Content'],
    responses: [
        new OA\Response(response: 200, description: 'success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),

            ],
            type      : 'object'
        )),
    ]
)]



class PartnersHandler extends Handler
{


    public function __construct(
        private readonly ConnectionService $connectionService,
        private readonly Partner $partner
    )
    {
    }


    public function __invoke(PartnersRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->connectionService->beginTransaction();

            $this->partner->name = $request->input('full_name');
            $this->partner->email = $request->input('email');
            $this->partner->message = $request->input('message');
            $this->partner->save();

            $this->connectionService->commit();
            return $this->successResponseWithMessage(
                'Thank you for your message. We will get back to you soon.'
            );
        }catch (\Exception $exception){
            $this->connectionService->rollBack();
            return $this->errorResponse($exception->getMessage());
        }

    }
}
