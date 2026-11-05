<?php

use App\Enums\RoleName;
use App\Models\Role;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('roles seeder creates each role once', function () {
    $this->seed(RolesSeeder::class);
    $this->seed(RolesSeeder::class);

    expect(Role::query()->pluck('name')->all())->toEqualCanonicalizing(RoleName::cases());
});
