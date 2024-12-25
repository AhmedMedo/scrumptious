<?php

declare(strict_types=1);

namespace App\Libraries\DataTables;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "DataTable",
    properties: [
        new OA\Property(
            property: "draw",
            type: "integer",
            example: 0
        ),
        new OA\Property(
            property: "recordsTotal",
            type: "integer",
            example: 15
        ),
        new OA\Property(
            property: "recordsFiltered",
            type: "integer",
            example: 10
        ),
        new OA\Property(
            property: "data",
            type: "array",
            items: new OA\Items(type: "object")
        ),
        new OA\Property(
            property: "input",
            properties: [
                new OA\Property(
                    property: "columns",
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(
                                property: "name",
                                type: "string",
                                example: "column_name"
                            ),
                            new OA\Property(
                                property: "searchable",
                                type: "boolean",
                                example: true
                            ),
                            new OA\Property(
                                property: "orderable",
                                type: "boolean",
                                example: true
                            ),
                            new OA\Property(
                                property: "search",
                                properties: [
                                    new OA\Property(
                                        property: "value",
                                        type: "string",
                                        example: "column value"
                                    ),
                                ],
                                type: "object"
                            ),
                        ],
                        type: "object"
                    )
                ),
            ],
            type: "object",
            additionalProperties: new OA\AdditionalProperties(
                description: "Whatever you pass to request body will be present here under the same key, with the same value.",
                anyOf: [
                    new OA\Schema(type: "string"),
                    new OA\Schema(type: "integer"),
                    new OA\Schema(type: "boolean"),
                ]
            )
        ),
    ]
)]
interface DataTablesOpenApi
{
    /**
     * This interface is internationally empty.
     * It should be used only for OpenApi documentation schema reference.
     * If you move this doc data tables abstract which is inherited by all data tables,
     * you will destroy the documentation because OpenAPI generator can detect children and parent classes
     * and inherit they docs and properties.
     */
}
