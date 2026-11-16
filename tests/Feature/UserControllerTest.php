<?php

use App\Enums\RoleName;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;

uses(RefreshDatabase::class);

test('guests are redirected from the members list', function () {
    $this->get(route('members.index'))->assertRedirect(route('login'));
});

test('the members list includes only members and project leads', function () {
    $this->withoutVite();

    $adminRole = Role::factory()->create(['name' => RoleName::Admin]);
    $memberRole = Role::factory()->create(['name' => RoleName::Member]);
    $projectLeadRole = Role::factory()->create(['name' => RoleName::ProjectLead]);

    $admin = User::factory()->create(['name' => 'Admin User', 'email' => 'admin@queueboard.test']);
    $admin->roles()->attach($adminRole);

    User::factory()->create(['name' => 'Unassigned', 'email' => 'unassigned@queueboard.test']);

    $zeta = User::factory()->create(['name' => 'Zeta', 'email' => 'zeta@queueboard.test']);
    $zeta->roles()->attach($projectLeadRole);

    $alpha = User::factory()->create(['name' => 'Alpha', 'email' => 'alpha@queueboard.test']);
    $alpha->roles()->attach($memberRole);

    $this->actingAs($admin)
        ->get(route('members.index'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Members/Index')
            ->where('members.data', [
                [
                    'id' => $alpha->id,
                    'name' => 'Alpha',
                    'email' => 'alpha@queueboard.test',
                    'roles' => ['MEMBER'],
                ],
                [
                    'id' => $zeta->id,
                    'name' => 'Zeta',
                    'email' => 'zeta@queueboard.test',
                    'roles' => ['PROJECT_LEAD'],
                ],
            ])
            ->where('members.total', 2)
        );
});

test('the requested page size selects which members are returned', function () {
    $this->withoutVite();

    $admin = User::factory()->create(['name' => 'Admin User', 'email' => 'admin@queueboard.test']);
    $admin->roles()->attach(Role::factory()->create(['name' => RoleName::Admin]));

    $memberRole = Role::factory()->create(['name' => RoleName::Member]);
    User::factory()
        ->count(5)
        ->sequence(fn ($sequence) => [
            'name' => sprintf('User %02d', $sequence->index),
            'email' => sprintf('user%02d@queueboard.test', $sequence->index),
        ])
        ->create()
        ->each(fn (User $user) => $user->roles()->attach($memberRole));

    $this->actingAs($admin)
        ->get(route('members.index', ['page' => 2, 'per_page' => 2]))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('members.data', 2)
            ->where('members.data.0.name', 'User 02')
            ->where('members.data.1.name', 'User 03')
            ->where('members.per_page', 2)
            ->where('members.current_page', 2)
            ->where('members.total', 5)
        );
});
