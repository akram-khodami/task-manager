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
        Project::factory(3)->create();

        // Get some users for project assignment
//        $users = User::inRandomOrder()->take(5)->get();
//
//        if ($users->isEmpty()) {
//
//            $this->call(UserSeeder::class);
//
//            $users = User::inRandomOrder()->take(5)->get();
//
//        }
//
//        Assign users to projects
//        Project::all()->each(function ($project) use ($users) {
//
//            $project->owner_id = $users->random(rand(1, 3))->pluck('id')->toArray();
//
//        });
    }
    }
