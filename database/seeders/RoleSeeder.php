<?php

namespace Database\Seeders;

use App\Components\Auth\Data\Entity\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'guard_name' => 'admin',
            ],
            [
                'name' => 'Admin',
                'guard_name' => 'admin',
            ],
            [
                'name' => 'User',
                'guard_name' => 'api',
            ],
            [
                'name' => 'Guest',
                'guard_name' => 'api',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate($role);
        }
    }
}
