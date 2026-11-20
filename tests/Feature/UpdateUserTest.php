<?php

use App\Enums\RoleName;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('guests are redirected when updating a member', function () {
    $member = User::factory()->create(['name' => 'Ada', 'email' => 'ada@queueboard.test']);

    $this->patch(route('members.update', $member), [
        'name' => 'Ada Lovelace',
        'email' => 'ada@queueboard.test',
    ])->assertRedirect(route('login'));

    expect($member->fresh()->name)->toBe('Ada');
});

test('an admin can update a member', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::factory()->create(['name' => RoleName::Admin]));

    $member = User::factory()->create([
        'name' => 'Ada',
        'email' => 'ada@queueboard.test',
        'password' => 'password',
    ]);

    $this->actingAs($admin)
        ->patch(route('members.update', $member), [
            'name' => 'Ada Lovelace',
            'email' => 'lovelace@queueboard.test',
        ])
        ->assertRedirect(route('members.index'));

    $member->refresh();

    expect($member->name)->toBe('Ada Lovelace')
        ->and($member->email)->toBe('lovelace@queueboard.test')
        ->and(Hash::check('password', $member->password))->toBeTrue()
        ->and($member->roles)->toHaveCount(0);
});

test('an admin can keep the same member email', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::factory()->create(['name' => RoleName::Admin]));

    $member = User::factory()->create(['name' => 'Ada', 'email' => 'ada@queueboard.test']);

    $this->actingAs($admin)
        ->patch(route('members.update', $member), [
            'name' => 'Ada Lovelace',
            'email' => 'ada@queueboard.test',
        ])
        ->assertRedirect(route('members.index'));

    expect($member->fresh()->name)->toBe('Ada Lovelace');
});

test('a non-admin cannot update a member', function () {
    $actor = User::factory()->create();
    $member = User::factory()->create(['name' => 'Ada', 'email' => 'ada@queueboard.test']);

    $this->actingAs($actor)
        ->patch(route('members.update', $member), [
            'name' => 'Ada Lovelace',
            'email' => 'ada@queueboard.test',
        ])
        ->assertForbidden();

    expect($member->fresh()->name)->toBe('Ada');
});

test('an admin cannot update another admin through the members endpoint', function () {
    $adminRole = Role::factory()->create(['name' => RoleName::Admin]);
    $admin = User::factory()->create();
    $admin->roles()->attach($adminRole);

    $otherAdmin = User::factory()->create(['name' => 'Other Admin', 'email' => 'other@queueboard.test']);
    $otherAdmin->roles()->attach($adminRole);

    $this->actingAs($admin)
        ->patch(route('members.update', $otherAdmin), [
            'name' => 'Changed',
            'email' => 'other@queueboard.test',
        ])
        ->assertForbidden();

    expect($otherAdmin->fresh()->name)->toBe('Other Admin');
});

test('member update requires a name and email', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::factory()->create(['name' => RoleName::Admin]));
    $member = User::factory()->create(['name' => 'Ada', 'email' => 'ada@queueboard.test']);

    $this->actingAs($admin)
        ->from(route('members.index'))
        ->patch(route('members.update', $member), [])
        ->assertRedirect(route('members.index'))
        ->assertSessionHasErrors(['name', 'email']);
});

test('a member email must stay unique when updating', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::factory()->create(['name' => RoleName::Admin]));

    User::factory()->create(['email' => 'taken@queueboard.test']);
    $member = User::factory()->create(['name' => 'Ada', 'email' => 'ada@queueboard.test']);

    $this->actingAs($admin)
        ->from(route('members.index'))
        ->patch(route('members.update', $member), [
            'name' => 'Ada',
            'email' => 'taken@queueboard.test',
        ])
        ->assertRedirect(route('members.index'))
        ->assertSessionHasErrors('email');

    expect($member->fresh()->email)->toBe('ada@queueboard.test');
});
