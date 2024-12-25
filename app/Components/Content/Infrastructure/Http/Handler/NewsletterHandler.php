<?php

namespace App\Components\Content\Infrastructure\Http\Handler;

use App\Components\Content\Data\Entity\Newsletter;
use App\Components\Content\Data\Entity\Partner;
use App\Components\Content\Infrastructure\Http\Request\NewsletterRequest;
use App\Components\Content\Infrastructure\Http\Request\PartnersRequest;
use App\Libraries\Base\Database\MySQL\ConnectionService;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\POST(
    path: '/api/v1/newsletter',
    description: 'newsletters',
    summary: 'newsletters',
    requestBody: new OA\RequestBody('#/components/requestBodies/NewsletterRequest'),
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



class NewsletterHandler extends Handler
{


    public function __construct(
        private readonly ConnectionService $connectionService,
        private readonly Newsletter $newsletter
    )
    {
    }


    public function __invoke(NewsletterRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->connectionService->beginTransaction();

            $this->newsletter->email = $request->input('email');
            $this->newsletter->save();

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
