<?php

use App\Enums\RoleName;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Database\Seeders\UsersSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('users seeder creates one admin with the admin role', function () {
    $this->seed(RolesSeeder::class);
    $this->seed(UsersSeeder::class);
    $this->seed(UsersSeeder::class);

    $admins = User::query()->where('email', 'admin@queueboard.test')->get();

    expect($admins)->toHaveCount(1)
        ->and($admins->first()->roles)->toHaveCount(1)
        ->and($admins->first()->roles->first()->name)->toBe(RoleName::Admin)
        ->and(Hash::check('password', $admins->first()->password))->toBeTrue();
});
