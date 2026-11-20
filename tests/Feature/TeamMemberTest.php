<?php

use App\Enums\RoleName;
use App\Enums\TeamRole;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests are redirected when adding a team member', function () {
    $team = Team::factory()->create();
    $member = User::factory()->create();

    $this->post(route('teams.members.store', $team), [
        'user_id' => $member->id,
        'role' => TeamRole::Member->value,
    ])->assertRedirect(route('login'));

    expect($team->members()->where('users.id', $member->id)->exists())->toBeFalse();
});

test('an admin can add a member to a team with a role', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::factory()->create(['name' => RoleName::Admin]));

    $team = Team::factory()->create();
    $member = User::factory()->create();

    $this->actingAs($admin)
        ->post(route('teams.members.store', $team), [
            'user_id' => $member->id,
            'role' => TeamRole::Member->value,
        ])
        ->assertRedirect(route('teams.index'));

    $membership = $team->members()->where('users.id', $member->id)->first();

    expect($membership)->not->toBeNull()
        ->and($membership->pivot->role)->toBe(TeamRole::Member);
});

test('an admin can add a project lead to a team', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::factory()->create(['name' => RoleName::Admin]));

    $team = Team::factory()->create();
    $lead = User::factory()->create();

    $this->actingAs($admin)
        ->post(route('teams.members.store', $team), [
            'user_id' => $lead->id,
            'role' => TeamRole::ProjectLead->value,
        ])
        ->assertRedirect(route('teams.index'));

    $membership = $team->members()->where('users.id', $lead->id)->first();

    expect($membership)->not->toBeNull()
        ->and($membership->pivot->role)->toBe(TeamRole::ProjectLead);
});

test('adding a team member requires a role', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::factory()->create(['name' => RoleName::Admin]));

    $team = Team::factory()->create();
    $member = User::factory()->create();

    $this->actingAs($admin)
        ->from(route('teams.index'))
        ->post(route('teams.members.store', $team), [
            'user_id' => $member->id,
        ])
        ->assertRedirect(route('teams.index'))
        ->assertSessionHasErrors('role');

    expect($team->members()->where('users.id', $member->id)->exists())->toBeFalse();
});

test('a non-admin cannot add someone to a team', function () {
    $actor = User::factory()->create();
    $team = Team::factory()->create();
    $member = User::factory()->create();

    $this->actingAs($actor)
        ->post(route('teams.members.store', $team), [
            'user_id' => $member->id,
            'role' => TeamRole::Member->value,
        ])
        ->assertForbidden();

    expect($team->members()->where('users.id', $member->id)->exists())->toBeFalse();
});

test('an admin cannot be added to a team', function () {
    $adminRole = Role::factory()->create(['name' => RoleName::Admin]);
    $admin = User::factory()->create();
    $admin->roles()->attach($adminRole);

    $team = Team::factory()->create();
    $otherAdmin = User::factory()->create();
    $otherAdmin->roles()->attach($adminRole);

    $this->actingAs($admin)
        ->from(route('teams.index'))
        ->post(route('teams.members.store', $team), [
            'user_id' => $otherAdmin->id,
            'role' => TeamRole::Member->value,
        ])
        ->assertRedirect(route('teams.index'))
        ->assertSessionHasErrors('user_id');

    expect($team->members()->where('users.id', $otherAdmin->id)->exists())->toBeFalse();
});

test('a user cannot be added to the same team twice', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::factory()->create(['name' => RoleName::Admin]));

    $team = Team::factory()->create();
    $member = User::factory()->create();
    $team->members()->attach($member->id, ['role' => TeamRole::Member->value]);

    $this->actingAs($admin)
        ->from(route('teams.index'))
        ->post(route('teams.members.store', $team), [
            'user_id' => $member->id,
            'role' => TeamRole::ProjectLead->value,
        ])
        ->assertRedirect(route('teams.index'))
        ->assertSessionHasErrors('user_id');

    expect($team->members()->where('users.id', $member->id)->count())->toBe(1);
});

test('an admin can remove a member from a team', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::factory()->create(['name' => RoleName::Admin]));

    $team = Team::factory()->create();
    $member = User::factory()->create();
    $team->members()->attach($member->id, ['role' => TeamRole::Member->value]);

    $this->actingAs($admin)
        ->delete(route('teams.members.destroy', [$team, $member]))
        ->assertRedirect(route('teams.index'));

    expect($team->members()->where('users.id', $member->id)->exists())->toBeFalse();
});

test('removing a user who is not on the team returns not found', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::factory()->create(['name' => RoleName::Admin]));

    $team = Team::factory()->create();
    $member = User::factory()->create();

    $this->actingAs($admin)
        ->delete(route('teams.members.destroy', [$team, $member]))
        ->assertNotFound();
});

test('a non-admin cannot remove someone from a team', function () {
    $actor = User::factory()->create();
    $team = Team::factory()->create();
    $member = User::factory()->create();
    $team->members()->attach($member->id, ['role' => TeamRole::Member->value]);

    $this->actingAs($actor)
        ->delete(route('teams.members.destroy', [$team, $member]))
        ->assertForbidden();

    expect($team->members()->where('users.id', $member->id)->exists())->toBeTrue();
});
