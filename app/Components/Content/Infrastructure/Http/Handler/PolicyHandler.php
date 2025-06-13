<?php

namespace App\Components\Content\Infrastructure\Http\Handler;

use App\Components\Content\Data\Entity\WebsiteSettingsEntity;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/v1/content/policies',
    description: 'Get privacy policy and terms PDF URLs',
    summary: 'Policies PDFs',
    tags: ['Content'],
    responses: [
        new OA\Response(response: 200, description: 'success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', properties: [
                    new OA\Property(property: 'privacy', type: 'string'),
                    new OA\Property(property: 'terms', type: 'string'),
                ], type: 'object'),
            ],
            type: 'object'
        )),
    ]
)]
class PolicyHandler extends Handler
{
    public function __invoke(): JsonResponse
    {
        $locale = app()->getLocale();
        $privacy = WebsiteSettingsEntity::where('key', 'privacy_and_policy')->first();
        $terms = WebsiteSettingsEntity::where('key', 'terms_and_condition')->first();

        return $this->successResponseWithData([
            'privacy' => $privacy?->getFirstMediaUrl('privacy_and_policy_' . $locale),
            'terms' => $terms?->getFirstMediaUrl('terms_and_condition_' . $locale),
        ]);
    }
}
