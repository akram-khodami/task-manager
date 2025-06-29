<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\TaskAttachment;
use Illuminate\Database\Seeder;

class TaskAttachmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some tasks for attachment creation
        $tasks = Task::inRandomOrder()->take(10)->get();

        if ($tasks->isEmpty()) {
            $this->call(TaskSeeder::class);
            $tasks = Task::inRandomOrder()->take(10)->get();
        }

        // Create attachments for each task
        $tasks->each(function ($task) {
            // Create some image attachments
            TaskAttachment::factory(2)->image()->create([
                'task_id' => $task->id,
            ]);

            // Create some document attachments
            TaskAttachment::factory(2)->document()->create([
                'task_id' => $task->id,
            ]);
        });
    }
} 