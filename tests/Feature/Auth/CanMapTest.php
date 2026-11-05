<?php

use App\Enums\RoleName;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;

uses(RefreshDatabase::class);

test('an admin can manage teams, projects, users, and roles', function () {
    $this->withoutVite();

    $user = User::factory()->create();
    $user->roles()->attach([
        Role::factory()->create(['name' => RoleName::Admin])->id,
        Role::factory()->create(['name' => RoleName::Member])->id,
    ]);

    expect($user->can('createTeam'))->toBeTrue()
        ->and($user->can('createProject'))->toBeTrue()
        ->and($user->can('createUser'))->toBeTrue()
        ->and($user->can('assignRole'))->toBeTrue();

    $this->actingAs($user)
        ->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('can', [
                'createTeam' => true,
                'createProject' => true,
                'createUser' => true,
                'assignRole' => true,
            ])
            ->where('auth.user.roles', ['ADMIN', 'MEMBER'])
        );
});

test('users without the admin role cannot manage teams, projects, users, or roles', function (RoleName $role) {
    $this->withoutVite();

    $user = User::factory()->create();
    $user->roles()->attach(Role::factory()->create(['name' => $role]));

    expect($user->can('createTeam'))->toBeFalse()
        ->and($user->can('createProject'))->toBeFalse()
        ->and($user->can('createUser'))->toBeFalse()
        ->and($user->can('assignRole'))->toBeFalse();

    $this->actingAs($user)
        ->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('can', [
                'createTeam' => false,
                'createProject' => false,
                'createUser' => false,
                'assignRole' => false,
            ])
        );
})->with([
    'member' => RoleName::Member,
    'project lead' => RoleName::ProjectLead,
]);

test('a guest receives no admin permissions', function () {
    $this->withoutVite();

    $this->get('/')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('can', [
                'createTeam' => false,
                'createProject' => false,
                'createUser' => false,
                'assignRole' => false,
            ])
            ->where('auth.user', null)
        );
});
