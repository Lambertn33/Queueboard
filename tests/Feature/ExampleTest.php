<?php

use Inertia\Testing\AssertableInertia;

test('the home page is rendered through inertia', function () {
    $this->withoutVite();

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Welcome')
            ->where('message', 'Laravel sent this page to Vue through Inertia.')
        );
});
