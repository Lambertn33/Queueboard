<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;

uses(RefreshDatabase::class);

test('guests are redirected from home to the login page', function () {
    $this->get(route('home'))->assertRedirect(route('login'));
});

test('the home page is rendered through inertia', function () {
    $this->withoutVite();

    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Welcome')
            ->where('message', 'Laravel sent this page to Vue through Inertia.')
        );
});
