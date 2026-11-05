<?php

use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('team members are users', function () {
    $team = Team::factory()->create();
    $user = User::factory()->create();

    $team->members()->attach($user);

    expect($team->members)->toHaveCount(1)
        ->and($team->members->first()->is($user))->toBeTrue()
        ->and($user->teams)->toHaveCount(1);
});

test('a project belongs to a team', function () {
    $project = Project::factory()->create();

    expect($project->team)->toBeInstanceOf(Team::class)
        ->and($project->team->projects)->toHaveCount(1);
});

test('a task belongs to a project and can be assigned to a user', function () {
    $task = Task::factory()->create();

    expect($task->project)->toBeInstanceOf(Project::class)
        ->and($task->assignee)->toBeInstanceOf(User::class)
        ->and($task->project->tasks)->toHaveCount(1)
        ->and($task->assignee->tasks)->toHaveCount(1);
});

test('a task can exist without an assignee', function () {
    $task = Task::factory()->unassigned()->create();

    expect($task->assignee)->toBeNull();
});
