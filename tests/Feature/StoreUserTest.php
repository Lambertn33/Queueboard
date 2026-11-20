<?php

use App\Enums\RoleName;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('guests are redirected when creating a member', function () {
    $this->post(route('members.store'), [
        'name' => 'Ada',
        'email' => 'ada@queueboard.test',
    ])->assertRedirect(route('login'));

    $this->assertDatabaseMissing(User::class, ['email' => 'ada@queueboard.test']);
});

test('an admin can create a member with the default password', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::factory()->create(['name' => RoleName::Admin]));

    $this->actingAs($admin)
        ->post(route('members.store'), [
            'name' => 'Ada',
            'email' => 'ada@queueboard.test',
        ])
        ->assertRedirect(route('members.index'));

    $member = User::query()->where('email', 'ada@queueboard.test')->first();

    expect($member)->not->toBeNull()
        ->and($member->name)->toBe('Ada')
        ->and(Hash::check('password', $member->password))->toBeTrue()
        ->and($member->roles)->toHaveCount(0);
});

test('a non-admin cannot create a member', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('members.store'), [
            'name' => 'Ada',
            'email' => 'ada@queueboard.test',
        ])
        ->assertForbidden();

    $this->assertDatabaseMissing(User::class, ['email' => 'ada@queueboard.test']);
});

test('member creation requires a name and email', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::factory()->create(['name' => RoleName::Admin]));

    $this->actingAs($admin)
        ->from(route('members.index'))
        ->post(route('members.store'), [])
        ->assertRedirect(route('members.index'))
        ->assertSessionHasErrors(['name', 'email']);
});

test('a member email must be unique', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::factory()->create(['name' => RoleName::Admin]));
    User::factory()->create(['email' => 'ada@queueboard.test']);

    $this->actingAs($admin)
        ->from(route('members.index'))
        ->post(route('members.store'), [
            'name' => 'Ada',
            'email' => 'ada@queueboard.test',
        ])
        ->assertRedirect(route('members.index'))
        ->assertSessionHasErrors('email');
});
