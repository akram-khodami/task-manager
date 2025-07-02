<?php

namespace Database\Seeders;

use App\Models\Folder;
use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some folders for task creation
        $folders = Folder::inRandomOrder()->take(5)->get();

        if ($folders->isEmpty()) {
            $this->call(FolderSeeder::class);
            $folders = Folder::inRandomOrder()->take(5)->get();
        }

        // Create tasks for each folder
        $folders->each(function ($folder) {
            // Create tasks with different statuses
            Task::factory(2)->todo()->create(['folder_id' => $folder->id]);
            Task::factory(2)->inProgress()->create(['folder_id' => $folder->id]);
            Task::factory(1)->done()->create(['folder_id' => $folder->id]);
            Task::factory(1)->cancelled()->create(['folder_id' => $folder->id]);

            // Create some high priority tasks
            Task::factory(2)->highPriority()->create(['folder_id' => $folder->id]);
            Task::factory(1)->criticalPriority()->create(['folder_id' => $folder->id]);
        });
    }
} 