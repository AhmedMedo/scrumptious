<?php

namespace Database\Seeders;

use App\Components\Content\Data\Entity\CategoryEntity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        CategoryEntity::firstOrCreate([
            'name' => 'Small Plate',
        ]);

        CategoryEntity::firstOrCreate([
            'name' => 'Large Plate',
        ]);

        CategoryEntity::firstOrCreate([
            'name' => 'Dessert',
        ]);

        CategoryEntity::firstOrCreate([
            'name' => 'Salad',
        ]);

        CategoryEntity::firstOrCreate([
            'name' => 'Soup',
        ]);

        CategoryEntity::firstOrCreate([
            'name' => 'Sides',
        ]);
    }
}
