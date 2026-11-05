<?php

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Seed the application roles.
     */
    public function run(): void
    {
        foreach (RoleName::cases() as $roleName) {
            Role::query()->firstOrCreate([
                'name' => $roleName,
            ]);
        }
    }
}
