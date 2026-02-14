<?php

namespace App\Components\Notification\Infrastructure\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewRecipeUploadedEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public string $recipeUuid,
        public string $recipeName,
        public ?string $recipeDescription = null,
        public array $categories = []
    ) {
    }
}
