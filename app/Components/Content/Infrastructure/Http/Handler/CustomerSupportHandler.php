<?php

namespace App\Components\Content\Infrastructure\Http\Handler;

use App\Components\Content\Data\Entity\CustomerSupport;
use App\Components\Content\Infrastructure\Http\Request\CustomerSupportRequest;
use App\Libraries\Base\Database\MySQL\ConnectionService;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\POST(
    path: '/api/v1/customer-support',
    description: 'customer-support',
    summary: 'customer-support',
    requestBody: new OA\RequestBody('#/components/requestBodies/CustomerSupportRequest'),
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



class CustomerSupportHandler extends Handler
{


    public function __construct(
        private readonly ConnectionService $connectionService,
        private readonly CustomerSupport $support
    )
    {
    }


    public function __invoke(CustomerSupportRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->connectionService->beginTransaction();

            $this->support->name = $request->input('full_name');
            $this->support->email = $request->input('email');
            $this->support->message = $request->input('message');
            $this->support->save();

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
