<?php

use App\Enums\RoleName;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a user can have more than one role', function () {
    $user = User::factory()->create();
    $member = Role::factory()->create(['name' => RoleName::Member]);
    $projectLead = Role::factory()->create(['name' => RoleName::ProjectLead]);

    $user->roles()->attach([$member->id, $projectLead->id]);

    expect($user->roles)->toHaveCount(2)
        ->and($user->roles->pluck('name')->all())->toEqualCanonicalizing([
            RoleName::Member,
            RoleName::ProjectLead,
        ])
        ->and($member->users->first()->is($user))->toBeTrue();
});
