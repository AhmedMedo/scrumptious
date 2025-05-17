<?php

namespace Database\Seeders;

use App\Components\Auth\Data\Entity\UserEntity;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        UserEntity::query()->where('email','=','admin@admin.com')->delete();
        $adminUser = Admin::query()->updateOrCreate([
            'email' => 'admin@scrumptious.com',
        ],[
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@scrumptious.com',
            'password' => bcrypt('password'),
        ]);

        $adminUser->assignRole('Admin');

        $superAdminUser = Admin::query()->firstOrCreate([
            'email' => 'superadmin@scrumptious.com',
        ],[
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'superadmin@scrumptious.com',
            'password' => bcrypt('password'),
        ]);

        $superAdminUser->assignRole('Super Admin');
    }
}
