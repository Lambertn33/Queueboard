<?php

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Seed the admin user and attach the admin role.
     */
    public function run(): void
    {
        $adminRole = Role::query()->where('name', RoleName::Admin)->firstOrFail();

        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@queueboard.test'],
            [
                'name' => 'Admin',
                'password' => 'password',
            ],
        );

        $admin->roles()->syncWithoutDetaching([$adminRole->id]);
    }
}
