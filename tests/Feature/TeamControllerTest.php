<?php

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;

uses(RefreshDatabase::class);

test('guests are redirected from the teams list', function () {
    $this->get(route('teams.index'))->assertRedirect(route('login'));
});

test('a short team list fits on one page', function () {
    $this->withoutVite();

    $user = User::factory()->create();
    $zeta = Team::factory()->create(['name' => 'Zeta']);
    $alpha = Team::factory()->create(['name' => 'Alpha']);

    $this->actingAs($user)
        ->get(route('teams.index'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Teams/Index')
            ->where('teams.data', [
                ['id' => $alpha->id, 'name' => 'Alpha', 'members_count' => 0, 'projects_count' => 0],
                ['id' => $zeta->id, 'name' => 'Zeta', 'members_count' => 0, 'projects_count' => 0],
            ])
            ->where('teams.per_page', 10)
            ->where('teams.current_page', 1)
            ->where('teams.last_page', 1)
            ->where('teams.total', 2)
            ->where('teams.next_page_url', null)
        );
});

test('teams that share a name stay in id order', function () {
    $this->withoutVite();

    $user = User::factory()->create();
    $first = Team::factory()->create(['name' => 'Alpha']);
    $second = Team::factory()->create(['name' => 'Alpha']);

    $this->actingAs($user)
        ->get(route('teams.index'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('teams.data', [
                ['id' => $first->id, 'name' => 'Alpha', 'members_count' => 0, 'projects_count' => 0],
                ['id' => $second->id, 'name' => 'Alpha', 'members_count' => 0, 'projects_count' => 0],
            ])
        );
});

test('the first page holds ten teams and reports the total', function () {
    $this->withoutVite();

    $user = User::factory()->create();
    Team::factory()
        ->count(16)
        ->sequence(fn ($sequence) => [
            'name' => sprintf('Team %02d', $sequence->index),
        ])
        ->create();

    $this->actingAs($user)
        ->get(route('teams.index'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('teams.data', 10)
            ->where('teams.data.0.name', 'Team 00')
            ->where('teams.data.9.name', 'Team 09')
            ->where('teams.current_page', 1)
            ->where('teams.last_page', 2)
            ->where('teams.total', 16)
        );
});

test('the requested page size selects which teams are returned', function () {
    $this->withoutVite();

    $user = User::factory()->create();
    Team::factory()
        ->count(5)
        ->sequence(fn ($sequence) => [
            'name' => sprintf('Team %02d', $sequence->index),
        ])
        ->create();

    $this->actingAs($user)
        ->get(route('teams.index', ['page' => 2, 'per_page' => 2]))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('teams.data', 2)
            ->where('teams.data.0.name', 'Team 02')
            ->where('teams.data.1.name', 'Team 03')
            ->where('teams.per_page', 2)
            ->where('teams.current_page', 2)
            ->where('teams.last_page', 3)
            ->where('teams.total', 5)
        );
});

test('page two returns the teams after the first page', function () {
    $this->withoutVite();

    $user = User::factory()->create();
    Team::factory()
        ->count(16)
        ->sequence(fn ($sequence) => [
            'name' => sprintf('Team %02d', $sequence->index),
        ])
        ->create();

    $this->actingAs($user)
        ->get(route('teams.index', ['page' => 2]))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('teams.data', 6)
            ->where('teams.data.0.name', 'Team 10')
            ->where('teams.data.5.name', 'Team 15')
            ->where('teams.current_page', 2)
            ->where('teams.last_page', 2)
            ->where('teams.total', 16)
        );
});
