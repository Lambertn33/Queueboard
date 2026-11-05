<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\TaskPriority;
use App\TaskStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'assignee_id' => User::factory(),
            'title' => fake()->sentence(4),
            'due_date' => fake()->date(),
            'status' => fake()->randomElement(TaskStatus::cases()),
            'priority' => fake()->randomElement(TaskPriority::cases()),
        ];
    }

    /**
     * Indicate that the task has no assignee.
     */
    public function unassigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'assignee_id' => null,
        ]);
    }
}
