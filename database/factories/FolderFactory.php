<?php

namespace Database\Factories;

use App\Models\Folder;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Folder>
 */
class FolderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'project_id' => Project::factory(),
            'parent_id' => null,
        ];
    }

    /**
     * Indicate that the folder has a parent folder.
     */
    public function withParent(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'parent_id' => Folder::factory()->create([
                    'project_id' => $attributes['project_id'],
                ])->id,
            ];
        });
    }
} 