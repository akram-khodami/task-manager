<?php

namespace Database\Factories;

use App\Models\Folder;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
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
        $dueDate = fake()->dateTimeBetween('now', '+2 months');
        
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(['todo', 'in_progress', 'done', 'cancelled']),
            'due_date' => fake()->boolean(80) ? $dueDate : null, // 80% chance of having a due date
            'priority' => fake()->randomElement(['low', 'medium', 'high', 'critical']),
            'folder_id' => Folder::factory(),
            'created_by' => User::factory(),
            'assigned_to' => fake()->boolean(70) ? User::factory() : null, // 70% chance of being assigned
        ];
    }

    /**
     * Indicate that the task is todo.
     */
    public function todo(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'todo',
        ]);
    }

    /**
     * Indicate that the task is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
        ]);
    }

    /**
     * Indicate that the task is done.
     */
    public function done(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'done',
        ]);
    }

    /**
     * Indicate that the task is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }

    /**
     * Indicate that the task is high priority.
     */
    public function highPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'high',
        ]);
    }

    /**
     * Indicate that the task is critical priority.
     */
    public function criticalPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'critical',
        ]);
    }
} 