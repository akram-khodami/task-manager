<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users for project assignment
        $users = User::inRandomOrder()->take(5)->get();

        if ($users->isEmpty()) {
            $this->call(UserSeeder::class);
            $users = User::inRandomOrder()->take(5)->get();
        }

        // Create projects with different statuses
        Project::factory(3)->active()->create();
        Project::factory(2)->completed()->create();
        Project::factory(1)->onHold()->create();

        // Assign users to projects
        Project::all()->each(function ($project) use ($users) {
            $project->users()->attach(
                $users->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
} 