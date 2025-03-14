<?php

namespace Database\Seeders;

use App\Components\Content\Data\Entity\CategoryEntity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $cat1 = CategoryEntity::firstOrCreate([
            'name' => 'Small Plate',
        ]);
        $cat1->clearMediaCollection();
        $cat1->addMediaFromUrl('https://picsum.photos/300/300')->toMediaCollection('image');

        $cat2 = CategoryEntity::firstOrCreate([
            'name' => 'Large Plate',
        ]);
        $cat2->clearMediaCollection();
        $cat2->addMediaFromUrl('https://picsum.photos/300/300')->toMediaCollection('image');

        $cat3 =  CategoryEntity::firstOrCreate([
            'name' => 'Dessert',
        ]);
        $cat3->clearMediaCollection();
        $cat3->addMediaFromUrl('https://picsum.photos/300/300')->toMediaCollection('image');


        $cat4 = CategoryEntity::firstOrCreate([
            'name' => 'Salad',
        ]);
        $cat4->clearMediaCollection();
        $cat4->addMediaFromUrl('https://picsum.photos/300/300')->toMediaCollection('image');


        $cat5= CategoryEntity::firstOrCreate([
            'name' => 'Soup',
        ]);
        $cat5->clearMediaCollection();
        $cat5->addMediaFromUrl('https://picsum.photos/300/300')->toMediaCollection('image');


        $cat6= CategoryEntity::firstOrCreate([
            'name' => 'Sides',
        ]);

        $cat6->clearMediaCollection();
        $cat6->addMediaFromUrl('https://picsum.photos/300/300')->toMediaCollection('image');

    }
}
