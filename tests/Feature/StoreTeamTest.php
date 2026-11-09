<?php

use App\Enums\RoleName;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests are redirected when creating a team', function () {
    $this->post(route('teams.store'), ['name' => 'Alpha'])
        ->assertRedirect(route('login'));

    $this->assertDatabaseMissing(Team::class, ['name' => 'Alpha']);
});

test('an admin can create a team', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::factory()->create(['name' => RoleName::Admin]));

    $this->actingAs($admin)
        ->post(route('teams.store'), ['name' => 'Alpha'])
        ->assertRedirect(route('teams.index'));

    $this->assertDatabaseHas(Team::class, ['name' => 'Alpha']);
});

test('a member cannot create a team', function () {
    $member = User::factory()->create();
    $member->roles()->attach(Role::factory()->create(['name' => RoleName::Member]));

    $this->actingAs($member)
        ->post(route('teams.store'), ['name' => 'Alpha'])
        ->assertForbidden();

    $this->assertDatabaseMissing(Team::class, ['name' => 'Alpha']);
});

test('a team name is required', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::factory()->create(['name' => RoleName::Admin]));

    $this->actingAs($admin)
        ->from(route('teams.index'))
        ->post(route('teams.store'), ['name' => ''])
        ->assertRedirect(route('teams.index'))
        ->assertSessionHasErrors('name');

    expect(Team::query()->count())->toBe(0);
});
