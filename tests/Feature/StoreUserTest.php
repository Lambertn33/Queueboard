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
        'roles' => [RoleName::Member->value],
    ])->assertRedirect(route('login'));

    $this->assertDatabaseMissing(User::class, ['email' => 'ada@queueboard.test']);
});

test('an admin can create a member with the default password', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::factory()->create(['name' => RoleName::Admin]));
    Role::factory()->create(['name' => RoleName::Member]);
    Role::factory()->create(['name' => RoleName::ProjectLead]);

    $this->actingAs($admin)
        ->post(route('members.store'), [
            'name' => 'Ada',
            'email' => 'ada@queueboard.test',
            'roles' => [RoleName::Member->value, RoleName::ProjectLead->value],
        ])
        ->assertRedirect(route('members.index'));

    $member = User::query()->where('email', 'ada@queueboard.test')->first();

    expect($member)->not->toBeNull()
        ->and($member->name)->toBe('Ada')
        ->and(Hash::check('password', $member->password))->toBeTrue()
        ->and($member->roles->pluck('name')->all())->toEqualCanonicalizing([
            RoleName::Member,
            RoleName::ProjectLead,
        ]);
});

test('a member cannot create another member', function () {
    $member = User::factory()->create();
    $member->roles()->attach(Role::factory()->create(['name' => RoleName::Member]));

    $this->actingAs($member)
        ->post(route('members.store'), [
            'name' => 'Ada',
            'email' => 'ada@queueboard.test',
            'roles' => [RoleName::Member->value],
        ])
        ->assertForbidden();

    $this->assertDatabaseMissing(User::class, ['email' => 'ada@queueboard.test']);
});

test('member creation requires a name, email, and at least one role', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::factory()->create(['name' => RoleName::Admin]));

    $this->actingAs($admin)
        ->from(route('members.index'))
        ->post(route('members.store'), [])
        ->assertRedirect(route('members.index'))
        ->assertSessionHasErrors(['name', 'email', 'roles']);
});

test('the admin role cannot be assigned when creating a member', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::factory()->create(['name' => RoleName::Admin]));

    $this->actingAs($admin)
        ->from(route('members.index'))
        ->post(route('members.store'), [
            'name' => 'Ada',
            'email' => 'ada@queueboard.test',
            'roles' => [RoleName::Admin->value],
        ])
        ->assertRedirect(route('members.index'))
        ->assertSessionHasErrors('roles.0');

    $this->assertDatabaseMissing(User::class, ['email' => 'ada@queueboard.test']);
});

test('a member email must be unique', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::factory()->create(['name' => RoleName::Admin]));
    Role::factory()->create(['name' => RoleName::Member]);
    User::factory()->create(['email' => 'ada@queueboard.test']);

    $this->actingAs($admin)
        ->from(route('members.index'))
        ->post(route('members.store'), [
            'name' => 'Ada',
            'email' => 'ada@queueboard.test',
            'roles' => [RoleName::Member->value],
        ])
        ->assertRedirect(route('members.index'))
        ->assertSessionHasErrors('email');
});
