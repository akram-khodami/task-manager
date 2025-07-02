<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\ProjectStatus;
use App\Enums\ProjectPriority;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-1 year', 'now');

        $endDate = fake()->dateTimeBetween($startDate, '+1 year');

        return [
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(ProjectStatus::values()),
            'start_date' => $startDate,
            'end_date' => fake()->boolean(70) ? $endDate : null, // 70% chance of having an end date
            'priority' => fake()->randomElement(ProjectPriority::values()),
            'owner_id' => User::factory(),
        ];
    }
}
