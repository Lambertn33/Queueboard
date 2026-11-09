<?php

use App\Enums\RoleName;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests are redirected when updating a team', function () {
    $team = Team::factory()->create(['name' => 'Alpha']);

    $this->patch(route('teams.update', $team), ['name' => 'Beta'])
        ->assertRedirect(route('login'));

    expect($team->fresh()->name)->toBe('Alpha');
});

test('an admin can update a team name', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::factory()->create(['name' => RoleName::Admin]));
    $team = Team::factory()->create(['name' => 'Alpha']);

    $this->actingAs($admin)
        ->patch(route('teams.update', $team), ['name' => 'Beta'])
        ->assertRedirect(route('teams.index'));

    expect($team->fresh()->name)->toBe('Beta');
});

test('an admin can keep the same team name', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::factory()->create(['name' => RoleName::Admin]));
    $team = Team::factory()->create(['name' => 'Alpha']);

    $this->actingAs($admin)
        ->patch(route('teams.update', $team), ['name' => 'Alpha'])
        ->assertRedirect(route('teams.index'));

    expect($team->fresh()->name)->toBe('Alpha');
});

test('a member cannot update a team', function () {
    $member = User::factory()->create();
    $member->roles()->attach(Role::factory()->create(['name' => RoleName::Member]));
    $team = Team::factory()->create(['name' => 'Alpha']);

    $this->actingAs($member)
        ->patch(route('teams.update', $team), ['name' => 'Beta'])
        ->assertForbidden();

    expect($team->fresh()->name)->toBe('Alpha');
});

test('a team name is required when updating', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::factory()->create(['name' => RoleName::Admin]));
    $team = Team::factory()->create(['name' => 'Alpha']);

    $this->actingAs($admin)
        ->from(route('teams.index'))
        ->patch(route('teams.update', $team), ['name' => ''])
        ->assertRedirect(route('teams.index'))
        ->assertSessionHasErrors('name');

    expect($team->fresh()->name)->toBe('Alpha');
});

test('a team name must stay unique when updating', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::factory()->create(['name' => RoleName::Admin]));
    Team::factory()->create(['name' => 'Beta']);
    $team = Team::factory()->create(['name' => 'Alpha']);

    $this->actingAs($admin)
        ->from(route('teams.index'))
        ->patch(route('teams.update', $team), ['name' => 'Beta'])
        ->assertRedirect(route('teams.index'))
        ->assertSessionHasErrors('name');

    expect($team->fresh()->name)->toBe('Alpha');
});
