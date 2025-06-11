<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\TaskAttachment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskAttachment>
 */
class TaskAttachmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fileName = fake()->fileName();
        $fileType = fake()->mimeType();
        $fileSize = fake()->numberBetween(1024, 1024 * 1024 * 10); // Between 1KB and 10MB

        return [
            'task_id' => Task::factory(),
            'file_name' => $fileName,
            'file_type' => $fileType,
            'file_size' => $fileSize,
            'file_path' => 'attachments/' . fake()->uuid() . '/' . $fileName,
            'uploaded_by' => User::factory(),
        ];
    }

    /**
     * Indicate that the attachment is an image.
     */
    public function image(): static
    {
        return $this->state(function (array $attributes) {
            $imageTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            
            return [
                'file_name' => fake()->word() . '.' . fake()->randomElement($imageExtensions),
                'file_type' => fake()->randomElement($imageTypes),
                'file_size' => fake()->numberBetween(1024, 1024 * 1024 * 2), // Between 1KB and 2MB
            ];
        });
    }

    /**
     * Indicate that the attachment is a document.
     */
    public function document(): static
    {
        return $this->state(function (array $attributes) {
            $documentTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            $documentExtensions = ['pdf', 'doc', 'docx'];
            
            return [
                'file_name' => fake()->word() . '.' . fake()->randomElement($documentExtensions),
                'file_type' => fake()->randomElement($documentTypes),
                'file_size' => fake()->numberBetween(1024 * 10, 1024 * 1024 * 5), // Between 10KB and 5MB
            ];
        });
    }
} 