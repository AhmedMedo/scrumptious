<?php

namespace Database\Seeders;

use App\Components\Subscription\Data\Entity\SubscriptionPlanEntity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionPlanEntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Weekly',
                'slug' => 'weekly',
                'description' => 'Weekly plan',
                'price' => 100,
            ],
            [
                'name' => 'Monthly',
                'slug' => 'monthly',
                'description' => 'Monthly plan',
                'price' => 200,
            ],
            [
                'name' => 'Yearly',
                'slug' => 'yearly',
                'description' => 'Yearly plan',
                'price' => 300,
            ],
            [
                'name' => 'Custom Plan',
                'slug' => 'custom',
                'description' => 'Custom plan',
                'price' => 400,
            ]
        ];

        foreach ($plans as $plan) {
            SubscriptionPlanEntity::query()->updateOrCreate([
                'slug' => $plan['slug'],
            ], [
                'name' => $plan['name'],
                'slug' => $plan['slug'],
                'description' => $plan['description'],
                'price' => $plan['price'],
            ]);
        }

    }
}
