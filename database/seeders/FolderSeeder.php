<?php

namespace Database\Seeders;

use App\Models\Folder;
use App\Models\Project;
use Illuminate\Database\Seeder;

class FolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some projects for folder creation
        $projects = Project::inRandomOrder()->take(3)->get();

        if ($projects->isEmpty()) {
            $this->call(ProjectSeeder::class);
            $projects = Project::inRandomOrder()->take(3)->get();
        }

        // Create folders for each project
        $projects->each(function ($project) {
            // Create main folders
            $mainFolders = Folder::factory(2)->create([
                'project_id' => $project->id,
            ]);

            // Create subfolders for each main folder
            $mainFolders->each(function ($mainFolder) {
                Folder::factory(1)->withParent()->create([
                    'project_id' => $mainFolder->project_id,
                ]);
            });
        });
    }
} 