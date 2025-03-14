<?php

namespace App\Components\Recipe\Presentation\ViewModel;

use phpseclib3\Math\PrimeField\Integer;

class RecipeViewModel
{

    public function __construct(
        public readonly string $uuid,
        public readonly string $title,
        public readonly ?integer $cookingMinutes = null,
        public readonly ?integer $totalCards = null,
        public readonly ?integer $totalProteins = null,
    )
    {
    }
}
