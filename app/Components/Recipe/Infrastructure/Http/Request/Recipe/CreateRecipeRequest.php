<?php

namespace App\Components\Recipe\Infrastructure\Http\Request\Recipe;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

/**
 * Class CreateRecipeRequest
 *
 * This class handles the validation and OpenAPI documentation for the request to create a new recipe.
 *
 * @package App\Components\Recipe\Infrastructure\Http\Request\Recipe
 */
#[OA\RequestBody(
    request: 'CreateRecipeRequest',
    required: true,
    content: new OA\JsonContent(
        required: ['title'],
        properties: [
            new OA\Property(property: 'title', description: 'The title of the recipe.', type: 'string', example: 'Spaghetti Carbonara'),
            new OA\Property(property: 'cooking_minutes', description: 'The cooking time in minutes.', type: 'integer', example: 30),
            new OA\Property(property: 'total_carbs', description: 'Total carbohydrates in the recipe.', type: 'integer', example: 50),
            new OA\Property(property: 'total_proteins', description: 'Total proteins in the recipe.', type: 'integer', example: 20),
            new OA\Property(property: 'total_fats', description: 'Total fats in the recipe.', type: 'integer', example: 15),
            new OA\Property(property: 'total_calories', description: 'Total calories in the recipe.', type: 'integer', example: 400),
            new OA\Property(property: 'youtube_video', description: 'Link to a YouTube video related to the recipe.', type: 'string', example: 'https://www.youtube.com/watch?v=12345'),
            new OA\Property(
                property: 'ingredients',
                description: 'List of ingredients required for the recipe.',
                type: 'array',
                items: new OA\Items(
                    properties: [
                        new OA\Property(property: 'name', description: 'Name of the ingredient.', type: 'string', example: 'Pasta'),
                    ]
                )
            ),
            new OA\Property(
                property: 'instructions',
                description: 'List of instructions to prepare the recipe.',
                type: 'array',
                items: new OA\Items(
                    properties: [
                        new OA\Property(property: 'name', description: 'Step-by-step instruction.', type: 'string', example: 'Boil water'),
                    ]
                )
            ),
            new OA\Property(
                property: 'categories',
                description: 'List of categories the recipe belongs to.',
                type: 'array',
                items: new OA\Items(
                    properties: [
                        new OA\Property(property: 'uuid', description: 'UUID of the category.', type: 'integer', example: 1),
                    ]
                )
            ),
            new OA\Property(
                property: 'image',
                description: 'Image of the recipe.',
                properties: [
                    new OA\Property(property: 'file_url', description: 'URL of the image.', type: 'string', example: 'https://example.com/image.jpg'),
                    new OA\Property(property: 'file_name', description: 'Name of the image.', type: 'string', example: 'image.jpg'),
                    new OA\Property(property: 'file_path', description: 'Path of the image.', type: 'string', example: 'images/recipe/1/image.jpg'),
                    new OA\Property(property: 'mime_type', description: 'Mime type of the image.', type: 'string', example: 'image/jpeg'),
                ],
                type: 'object'
            ),


        ],
    )
)]
class CreateRecipeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'cooking_minutes' => 'nullable|integer',
            'total_carbs' => 'nullable|integer',
            'total_proteins' => 'nullable|integer',
            'total_fats' => 'nullable|integer',
            'total_calories' => 'nullable|integer',
            'youtube_video' => 'nullable|string',
            'ingredients' => 'nullable|array',
            'ingredients.*.content' => 'required|string',
            'instructions' => 'nullable|array',
            'instructions.*.content' => 'required|string',
            'categories' => 'nullable|array',
            'categories.*.uuid' => 'required|integer|exists:categories,uuid',
            'image'             => 'nullable|array',
            'image.file_url'    => 'nullable|string',
            'image.file_name'   => 'nullable|string',
            'image.file_path'   => 'nullable|string',
            'image.mime_type'   => 'nullable|string',
        ];
    }
}
