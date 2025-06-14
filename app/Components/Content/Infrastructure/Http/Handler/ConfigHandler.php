<?php

namespace App\Components\Content\Infrastructure\Http\Handler;

use App\Components\Content\Application\Mapper\CountryViewMapper;
use App\Components\Content\Application\Query\WebsiteSettingsQueryInterface;
use App\Components\Content\Application\Repository\CountryRepositoryInterface;
use App\Components\Content\Data\Entity\CountryEntity;
use App\Components\GiftCards\Application\Query\CategoryQueryInterface;
use App\Components\GiftCards\Application\Query\DesignTemplateQueryInterface;
use App\Components\GiftCards\Application\Query\OccasionQueryInterface;
use App\Components\GiftCards\Data\Entity\Events\ContractedAvenuesEntity;
use App\Components\GiftCards\Data\Entity\VendorEntity;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/v1/content/config',
    description: 'Get config',
    summary: 'Get config',
    tags: ['Content'],
    responses: [
        new OA\Response(response: 200, description: 'success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(properties: [
                    new OA\Property(property: 'contents', type: 'array', items: new OA\Items(properties: [
                        new OA\Property(property: 'type', type: 'string', enum: ['privacy', 'terms']),
                        new OA\Property(property: 'pdf', type: 'string'),
                        new OA\Property(property: 'html', type: 'string'),
                    ])),
                    new OA\Property(property: 'basic_data', properties: [
                        new OA\Property(property: 'categories', type: 'array', items: new OA\Items(properties: [
                            new OA\Property(property: 'uuid', type: 'string'),
                            new OA\Property(property: 'name', type: 'string'),
                            new OA\Property(property: 'created_at', type: 'string'),
                        ])),
                        new OA\Property(property: 'occasions', type: 'array', items: new OA\Items(properties: [
                            new OA\Property(property: 'uuid', type: 'string'),
                            new OA\Property(property: 'name', type: 'string'),
                            new OA\Property(property: 'image', type: 'string'),
                            new OA\Property(property: 'created_at', type: 'string'),
                        ])),
                        new OA\Property(property: 'templates', type: 'array', items: new OA\Items(properties: [
                            new OA\Property(property: 'uuid', type: 'string'),
                            new OA\Property(property: 'name', type: 'string'),
                            new OA\Property(property: 'image', type: 'string'),
                            new OA\Property(property: 'created_at', type: 'string'),
                        ])),
                        new OA\Property(property: 'contracted_avenues', type: 'array', items: new OA\Items(properties: [
                            new OA\Property(property: 'uuid', type: 'string'),
                            new OA\Property(property: 'name', type: 'string'),
                            new OA\Property(property: 'location_address', type: 'string'),
                            new OA\Property(property: 'location_url', type: 'string'),
                            new OA\Property(property: 'created_at', type: 'string'),
                        ])),
                        new OA\Property(property: 'countries', type: 'array', items: new OA\Items(properties: [
                            new OA\Property(property: 'uuid', type: 'string'),
                            new OA\Property(property: 'name', type: 'string'),
                            new OA\Property(property: 'code', type: 'string'),
                            new OA\Property(property: 'created_at', type: 'string'),
                        ])),
                        new OA\Property(property: 'vendors', type: 'array', items: new OA\Items(properties: [
                            new OA\Property(property: 'name', type: 'string'),
                            new OA\Property(property: 'slug', type: 'string'),
                        ])),
                    ], type: 'object'),
                ])),
            ],
            type: 'object'
        )),
    ]
)]
class ConfigHandler extends Handler
{
    public function __construct(
        private readonly WebsiteSettingsQueryInterface $websiteSettingsQuery,
        private readonly CategoryQueryInterface $categoryQuery,
        private readonly OccasionQueryInterface $occasionQuery,
        private readonly DesignTemplateQueryInterface $designTemplateQuery,
        private readonly CountryViewMapper $countryViewMapper,
        private readonly CountryRepositoryInterface $countryQuery,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $websiteSettings = $this->websiteSettingsQuery->first();
        $currentLocale = app()->getLocale();
        return $this->successResponseWithData([
            'contents' => [
                [
                    'type' => 'privacy',
                    'pdf' => $websiteSettings?->getFirstMediaUrl('privacy_and_policy_' . $currentLocale),
                    'html' => $websiteSettings?->getAttribute('privacy_and_policy_' . $currentLocale)
                ],
                [
                    'type' => 'terms',
                    'pdf' => $websiteSettings?->getFirstMediaUrl('terms_and_condition_' . $currentLocale),
                    'html' => $websiteSettings?->getAttribute('terms_and_condition_' . $currentLocale)
                ],
            ],
            'basic_data' => [
                'categories' => $this->categoryQuery->findAll()->map(function ($category) {
                    return [
                        'uuid' => $category->getKey(),
                        'name' => $category->name,
                        'is_active' => $category->is_active,
                        'created_at' => $category->created_at,
                    ];
                }),
                'occasions' => $this->occasionQuery->findAll()->map(function ($occasion) {
                    return [
                        'uuid' => $occasion->getKey(),
                        'name' => $occasion->name,
                        'image' => $occasion->getFirstMediaUrl('image') ?? null,
                        'created_at' => $occasion->created_at,
                    ];
                }),
                'templates' => $this->designTemplateQuery->findAll()->map(function ($template) {
                    return [
                        'uuid' => $template->getKey(),
                        'name' => $template->name,
                        'image' => $template->getFirstMediaUrl('images') ?? null,
                        'created_at' => $template->created_at,
                    ];
                }),
                'contracted_avenues' => ContractedAvenuesEntity::all()->map(function ($contractedAvenue) {
                    return [
                        'uuid' => $contractedAvenue->getKey(),
                        'name' => $contractedAvenue->name,
                        'location_address' => $contractedAvenue->location_address,
                        'location_url' => $contractedAvenue->location_url,
                        'created_at' => $contractedAvenue->created_at,
                    ];
                }),
                'countries' => $this->countryViewMapper->toArray(
                    $this->countryQuery->findOnlyForGiftCards()
                ),
                "vendors" => VendorEntity::all()->map(fn(VendorEntity $vendorEntity) => [
                    "name" => $vendorEntity->name,
                    "slug" => $vendorEntity->slug,
                ])->merge([
                    [
                        "name" => "Giftit",
                        "slug" => "giftit",
                    ]
                ])->toArray(),

            ]
        ]);
    }
}
